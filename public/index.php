<?php
/**
 * Created by PhpStorm.
 * User: xiaozhuangyuan
 * Date: 2018/10/18
 * Time: 18:24
 */

//时区设置
ini_set('date.timezone', 'Asia/Shanghai');

// 调试模式开关
define("APP_DEBUG", false);

// 定义应用根目录,可更改此目录
define('APP_ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// 定义应用目录
define('APP_PATH', APP_ROOT . 'app' . DIRECTORY_SEPARATOR);

//定义应用根命名空间
define('APP_ROOT_NAMESPACE', 'app');

// 定义应用运行时目录
define('APP_RUNTIME', APP_PATH . 'runtime' . DIRECTORY_SEPARATOR);

// 定义资源目录
define('APP_RESOURCES',APP_PATH . 'resources' . DIRECTORY_SEPARATOR);

require APP_PATH . 'start.php';