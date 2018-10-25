<?php
namespace business\validators;

use Yii;
use common\models\Validator;

class DateValidator extends Validator{

    public $message;

    public $beforeDate;

    public $afterDate;

    protected function validateValue($date){
        if(!$unixTime = strtotime($date))return $this->message;
        if(date('Y-m-d', $unixTime) != $date)return $this->message;
        if(!is_null($this->beforeDate)){
            if(!$beforeUnixTime = strtotime($this->beforeDate))return $this->message;
            if(date('Y-m-d', $beforeUnixTime) != $this->beforeDate)return $this->message;
            if($unixTime > $beforeUnixTime)return $this->message;
        }
        if(!is_null($this->afterDate)){
            if(!$afterUnixTime = strtotime($this->afterDate))return $this->message;
            if(date('Y-m-d', $afterUnixTime) != $this->afterDate)return $this->message;
            if($unixTime < $afterUnixTime)return $this->message;
        }
        return true;
    }
}
