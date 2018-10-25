<?php
namespace mobile\modules\member\validators;

use Yii;
use common\models\Validator;

class CValidator extends Validator{

    public $validValue;
    public $startTime;
    public $endTime;
    public $invalidTimeMessage;
    public $message;

    protected function validateValue($c){
        if(is_string($this->validValue)){
            if($c != $this->validValue)return $this->message;
        }elseif(is_array($this->validValue)){
            if(!in_array($c, $this->validValue))return $this->message;
        }
        if(!is_null($this->startTime)){
            $startUnixTime = (int)strtotime($this->startTime);
            if(Yii::$app->time->unixTime < $startUnixTime)return $this->invalidTimeMessage;
        }
        if(!is_null($this->endTime)){
            $endUnixTime = (int)strtotime($this->endTime);
            if(Yii::$app->time->unixTime > $endUnixTime)return $this->invalidTimeMessage;
        }
        return true;
    }
}
