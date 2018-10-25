<?php
namespace api\validators\MusicFestival;

use Yii;
use common\models\Validator;
use common\ActiveRecord\MusicFestivalAppointmentAR;

class UserMobileValidator extends Validator{

    public $message;

    protected function validateValue($mobile){
        return MusicFestivalAppointmentAR::find()->
            where(['user_mobile' => $mobile])->
            andWhere(['status' => 1])->
            exists() ? $this->message : true;
    }
}
