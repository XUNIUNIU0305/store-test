<?php
namespace business\modules\bank\validators;

use Yii;
use common\models\Validator;
use business\models\parts\SmsCaptcha;

class CaptchaValidator extends Validator{

    public $mobile;
    public $message;

    protected function validateValue($captcha){
        if(empty($this->mobile))return $this->message;
        if(SmsCaptcha::validateCaptcha($this->mobile, $captcha)){
            return true;
        }else{
            return $this->message;
        }
    }
}
