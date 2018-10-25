<?php
namespace business\validators;

use Yii;
use common\models\Validator;
use common\ActiveRecord\BusinessUserAR;

class MobileValidator extends Validator{

    public $message;
    public $exist;

    protected function validateValue($mobile){
        if($mobile > 19999999999 || $mobile < 10000000000)return $this->message;
        if(BusinessUserAR::findOne(['mobile' => $mobile])){
            return $this->exist;
        }else{
            return true;
        }
    }
}
