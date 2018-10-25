<?php
namespace api\validators\MusicFestival;

use Yii;
use common\models\Validator;
use common\ActiveRecord\MusicFestivalAR;

class IdValidator extends Validator{

    public $message;

    protected function validateValue($id){
        if($id == 0)return true;
        return MusicFestivalAR::find()->
            where(['id' => $id])->
            exists() ? : $this->message;
    }
}
