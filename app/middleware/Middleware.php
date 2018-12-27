<?php
/**
 * Created by PhpStorm.
 * User: xiaozhuangyuan
 * Date: 2018/12/19
 * Time: 18:06
 */

namespace app\middleware;

use Xiaozhuangyuan\Srouter\Middleware as MDL;
use app\controllers\Controller;

class Middleware extends Controller implements MDL
{

    public function handle(){
        return true;
    }

}