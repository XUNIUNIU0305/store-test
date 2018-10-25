<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/6/7
 * Time: 上午9:44
 */

namespace mobile\modules\member\models;

class RegisterModel extends \custom\models\RegisterModel
{

    public function signUp(){
        $callback = parent::signUp();
        if($callback === false){
            return ['url' => '/'];
        }else{
            return $callback;
        }
    }
}
