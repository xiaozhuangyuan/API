<?php
/**
 * Created by PhpStorm.
 * User: xiaozhuangyuan
 * Date: 2018/12/19
 * Time: 18:05
 */

namespace app\controllers;

use WebGeeker\Validation\Validation;

class Controller
{

    //当前用户信息
    private static $userInfo = [];

    /**
     * 获取当前的用户信息
     * @return array
     */
    public function getUserInfo(){
        return self::$userInfo;
    }

    /**
     * 设置当前用户信息
     * @param array $userInfo
     */
    public function setUserInfo(array $userInfo = []){
        self::$userInfo = $userInfo;
    }

    /**
     * @param $params
     * @param $validations
     * @param bool $output
     * @param bool $ignoreRequired
     * @return bool|string
     */
    public function validate($params, $validations,$output = true, $ignoreRequired = false)
    {
        try {
            Validation::validate($params, $validations, $ignoreRequired);
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

}