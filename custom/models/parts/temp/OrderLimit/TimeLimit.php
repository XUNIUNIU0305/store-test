<?php
namespace custom\models\parts\temp\OrderLimit;

use Yii;
use yii\base\Object;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use custom\models\parts\ItemInCart;

class TimeLimit extends Object{

    private $_timeLimit;

    public function init(){
        if(Yii::$app->user->isGuest)throw new InvalidCallException;
        try{
            $this->_timeLimit = include(__DIR__ . '/time.php');
        }catch(\Exception $e){
            $this->_timeLimit = [];
        }
    }

    public function getHasTimeLimit(){
        return empty($this->_timeLimit) ? false : true;
    }

    public function isLimitProduct(ItemInCart $itemInCart){
        return isset($this->_timeLimit[$itemInCart->productId]);
    }

    public function validateTimeLimit(ItemInCart $itemInCart){
        if($timeLimit = $this->getTimeLimit($itemInCart)){
            if(Yii::$app->time->unixTime >= $timeLimit['start']){
                if($timeLimit['end'] === false){
                    return true;
                }else{
                    return (Yii::$app->time->unixTime <= $timeLimit['end']);
                }
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    public function getTimeLimit(ItemInCart $itemInCart, bool $string = false){
        if($this->isLimitProduct($itemInCart)){
            $timeLimitConfig = $this->_timeLimit[$itemInCart->productId];
            if(is_numeric($timeLimitConfig) || is_string($timeLimitConfig)){
                $timeStart = $timeLimitConfig;
                $timeEnd = false;
            }elseif(is_array($timeLimitConfig)){
                $timeStart = $timeLimitConfig[0];
                $timeEnd = $timeLimitConfig[1] ?? false;
            }else{
                throw new InvalidConfigException('unavailable time limit configuration');
            }
            foreach(['timeStart' => $timeStart, 'timeEnd' => $timeEnd] as $k => $v){
                if(is_string($v) && !is_numeric($v)){
                    if((${$k} = strtotime($v)) === false)throw new InvalidConfigException('unavailable time limit configuration');
                }
            }
            if($string){
                return [
                    'start' => date('Y-m-d H:i:s', $timeStart),
                    'end' => $timeEnd === false ? false : date('Y-m-d H:i:s', $timeEnd),
                ];
            }else{
                return [
                    'start' => $timeStart,
                    'end' => $timeEnd === false ? false : $timeEnd,
                ];
            }
        }else{
            return false;
        }
    }
}
