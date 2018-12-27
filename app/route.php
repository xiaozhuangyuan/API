<?php
/**
 * Created by PhpStorm.
 * User: xiaozhuangyuan
 * Date: 2018/10/15
 * Time: 15:39
 */

use Xiaozhuangyuan\Srouter\Router;

$router = new Router();

$router->get('/', function () {
    echo 'hello!';
});


$router->dispatch();