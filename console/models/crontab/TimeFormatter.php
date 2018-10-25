<?php
namespace console\models\crontab;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;

class TimeFormatter extends Object{

    public $availableTime = [];

    private $_availableTime;

    public function init(){
        if(!is_array($this->availableTime))return new InvalidConfigExpception('param [availableTime] must be an array');
        $this->_availableTime = $this->availableTime;
    }

    public function setAvailableTime(array $time){
        $this->_availableTime = $time;
    }

    public function format(string $time, $return = 'throw'){
        if($time == '')return Yii::$app->EC->callback($return, 'unavailable time');
        if($time === '*' && !empty($this->_availableTime))return $this->_availableTime;
        $times = explode(',', $time);
        foreach($times as $one){
            if(!in_array($one, $this->_availableTime))return Yii::$app->EC->callback($return, 'unavailable time');
        }
        return $times;
    }
}
