<?php
/**
 * Created by PhpStorm.
 * User: xiaozhuangyuan
 * Date: 2018/10/18
 * Time: 16:56
 */

require APP_ROOT . 'framework/vendor/autoload.php';

if (APP_DEBUG) {
    //在代码中注册漂亮的处理程序
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
} else {
    // 关闭错误报告
    error_reporting(0);
}


//加载通用函数
require APP_PATH . 'common.php';
//加载路由
require APP_PATH . 'route.php';