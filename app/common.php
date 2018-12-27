<?php
/**
 * Created by PhpStorm.
 * User: xiaozhuangyuan
 * Date: 2018/10/16
 * Time: 14:27
 */

/**
 * 递归创建目录
 * @param $dir
 * @return bool
 */
function directory($dir){
    return  is_dir ( $dir ) or directory(dirname( $dir )) and  @mkdir ( $dir , 0777);
}

/**
 * 获取实例化模型对象
 * @param $model
 * @param mixed ...$args
 * @return mixed
 */
function model($model,...$args){
    if(strpos($model,'app') === 0){
        return  new $model(...$args);
    }else{
        $model = 'app\\models\\'.$model;
        return new $model(...$args);
    }
}

/**
 * 获取授权信息
 * @return bool|string
 */
function getBasicAuthorized(){
    if(isset($_SERVER['HTTP_AUTHORIZATION'])){//其他服务器如 Nginx  Authorization
        if (strpos($_SERVER['HTTP_AUTHORIZATION'], 'Basic') === 0) {
            return substr($_SERVER['HTTP_AUTHORIZATION'], 6);
        }
    }
    return '';
}


/**
 * http返回码
 * @param int $num
 */
function httpStatus($num=200){
    static $http = array (
        100 => "HTTP/1.1 100 Continue",
        101 => "HTTP/1.1 101 Switching Protocols",
        200 => "HTTP/1.1 200 OK",
        201 => "HTTP/1.1 201 Created",
        202 => "HTTP/1.1 202 Accepted",
        203 => "HTTP/1.1 203 Non-Authoritative Information",
        204 => "HTTP/1.1 204 No Content",
        205 => "HTTP/1.1 205 Reset Content",
        206 => "HTTP/1.1 206 Partial Content",
        300 => "HTTP/1.1 300 Multiple Choices",
        301 => "HTTP/1.1 301 Moved Permanently",
        302 => "HTTP/1.1 302 Found",
        303 => "HTTP/1.1 303 See Other",
        304 => "HTTP/1.1 304 Not Modified",
        305 => "HTTP/1.1 305 Use Proxy",
        307 => "HTTP/1.1 307 Temporary Redirect",
        400 => "HTTP/1.1 400 Bad Request",
        401 => "HTTP/1.1 401 Unauthorized",
        402 => "HTTP/1.1 402 Payment Required",
        403 => "HTTP/1.1 403 Forbidden",
        404 => "HTTP/1.1 404 Not Found",
        405 => "HTTP/1.1 405 Method Not Allowed",
        406 => "HTTP/1.1 406 Not Acceptable",
        407 => "HTTP/1.1 407 Proxy Authentication Required",
        408 => "HTTP/1.1 408 Request Time-out",
        409 => "HTTP/1.1 409 Conflict",
        410 => "HTTP/1.1 410 Gone",
        411 => "HTTP/1.1 411 Length Required",
        412 => "HTTP/1.1 412 Precondition Failed",
        413 => "HTTP/1.1 413 Request Entity Too Large",
        414 => "HTTP/1.1 414 Request-URI Too Large",
        415 => "HTTP/1.1 415 Unsupported Media Type",
        416 => "HTTP/1.1 416 Requested range not satisfiable",
        417 => "HTTP/1.1 417 Expectation Failed",
        500 => "HTTP/1.1 500 Internal Server Error",
        501 => "HTTP/1.1 501 Not Implemented",
        502 => "HTTP/1.1 502 Bad Gateway",
        503 => "HTTP/1.1 503 Service Unavailable",
        504 => "HTTP/1.1 504 Gateway Time-out"
    );
    header($http[$num]);
}

/**
 * 获取客户端请求的数据
 * 现在只支持application/x-www-form-urlencoded
 * @return array
 */
function request()
{
    parse_str(file_get_contents('php://input'), $data);
    if (!empty($_GET)) {
        if (!empty($data)) {
            return array_merge($_GET, $data);
        } else {
            return $_GET;
        }
    }
    return $data;
}

/**
 * 验证数据
 * @param $params
 * @param $validations
 * @param bool $output
 * @param bool $ignoreRequired
 * @return bool|string
 */
function validate($params, $validations,$output = true, $ignoreRequired = false)
{
    try {
        WebGeeker\Validation\Validation::validate($params, $validations, $ignoreRequired);
    } catch (\Exception $e) {
        if($output){
            httpStatus(400);
            response_json(['code' => 1, 'msg' => $e->getMessage()]);
            exit();
        }else{
            return $e->getMessage();
        }
    }
    return true;
}

/**
 * 将数组转换成json数据，并响应json格式的头
 * @param array $data
 */
function response_json($data)
{
    header('Content-type:text/json');
    echo json_encode($data);
}

/**
 * 获取请求ip
 * @return string
 */
function ip()
{
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches [0] : '';
}

/**
 * 获取配置文件
 * @param string $params
 * @return array|mixed|string
 */
function config($params = '')
{
    static $config = [];
    if (empty($config)) {
        $config = include APP_PATH . 'config.php';
    }
    if ($params == '') {
        return $config;
    } elseif (isset($config[$params])) {
        return $config[$params];
    } else {
        return '';
    }
}

/**
 * 获取数据库操作对象
 * @return \Medoo\Medoo
 */
function db()
{
    static $database;
    if (!is_object($database)) {
        // 初始化配置
        try {
            $database = new \Medoo\Medoo(config('database'));
        } catch (\PDOException $e) {
            response_json(['code' => -1, 'msg' => $e->getMessage()]);
            exit();
        }
    }
    return $database;
}

/**
 * 操作日志
 * @param string $name
 * @return \Monolog\Logger
 * @throws Exception
 */
function sLog($name = 'sapi')
{
    static $log;
    static $logHandler;
    if (!is_object($log) || !is_object($logHandler)) {
        $log = new \Monolog\Logger($name);
        $logHandler = new \Monolog\Handler\StreamHandler(APP_RUNTIME . 'log/' . date('Ym') . '/' . date('d') . '.log', \Monolog\Logger::INFO);
        $log->pushHandler($logHandler);
    }

    return $log;
}

/**
 * 缓存
 * @return \Symfony\Component\Cache\Simple\FilesystemCache
 */
function cache()
{
    static $cache;
    if (!is_object($cache)) {
        $cache = new Symfony\Component\Cache\Simple\FilesystemCache('',0,APP_RUNTIME.'cache');
    }

    return $cache;
}
