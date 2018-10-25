<?php
namespace common\models\temp\djy;

use Yii;
use yii\base\Object;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use common\models\parts\Order;

class Statistics extends Object{

    protected $status;
    protected $skuIds;
    private $_expireTime = 60;
    private $_djy;

    public function init(){
        if(!$this->_djy)throw new InvalidConfigException;
        $this->status = implode(',', [Order::STATUS_UNDELIVER, Order::STATUS_DELIVERED, Order::STATUS_CONFIRMED, Order::STATUS_CLOSED]);
        $this->skuIds = implode(',', $this->getDjy()->getSkuIds());
    }

    public function setDjy(Djy $djy){
        $this->_djy = $djy;
    }

    public function getDjy(){
        return $this->_djy;
    }

    public function setExpireTime(int $time){
        if($time < 0 || $time > 29 * 86400){
            throw new InvalidCallException;
        }else{
            $this->_expireTime = $time;
        }
    }

    public function getExpireTime(){
        return $this->_expireTime;
    }

    protected function getCache(string $name, string $functionName, array $params = []){
        if($data = Yii::$app->cache->get($name)){
            return $data;
        }else{
            if($data = call_user_func_array([$this, $functionName], $params)){
                Yii::$app->cache->set($name, $data, $this->_expireTime);
            }
            return $data;
        }
    }
}
