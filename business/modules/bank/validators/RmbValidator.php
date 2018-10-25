<?php
namespace business\modules\bank\validators;

use Yii;
use common\models\Validator;

class RmbValidator extends Validator{

    public $message;

    protected function validateValue($rmb){
        if($rmb > 0 && $rmb % 100 === 0){
            return true;
        }else{
            return $this->message;
        }
    }
}
