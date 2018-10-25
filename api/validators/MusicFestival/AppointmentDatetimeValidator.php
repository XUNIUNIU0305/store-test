<?php
namespace api\validators\MusicFestival;

use Yii;
use common\models\Validator;
use yii\validators\DateValidator;

class AppointmentDatetimeValidator extends Validator{

    public $message;
    public $unavailableTime;

    protected function validateValue($time){
        if($time == '0000-01-01 00:00:00')return true;
        $dateValidator = new DateValidator([
            'type' => DateValidator::TYPE_DATETIME,
            'format' => 'php:Y-m-d H:i:s',
            'min' => date('Y-m-d H:i:s', time() + 3600),
            'tooSmall' => $this->unavailableTime,
            'message' => $this->message,
        ]);
        if($dateValidator->validate($time, $error)){
            return true;
        }else{
            return (int)$error;
        }
    }
}
