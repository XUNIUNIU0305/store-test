<?php
namespace custom\models\parts\trade;

use Yii;
use common\models\parts\trade\PayLogAbstract;
use common\ActiveRecord\CustomUserPayLogAR;
use yii\base\InvalidCallException;

class PayLog extends PayLogAbstract{

    //custom_user_pay_log表主键
    public $id;

    protected $AR;

    public function init(){
        if(!$this->id ||
            !$this->AR = CustomUserPayLogAR::findOne($this->id))throw new InvalidCallException;
    }

    /**
     * 获取消费金额
     *
     * @return float
     */
    public function getPayAmount(){
        return $this->AR->pay_amount;
    }

    /**
     * 获取消费前余额
     *
     * @return float
     */
    public function getRMBBefore(){
        return (float)$this->AR->rmb_before;
    }

    /**
     * 获取消费后余额
     *
     * @return float
     */
    public function getRMBAfter(){
        return (float)$this->AR->rmb_after;
    }

    /**
     * 获取消费时间
     *
     * @param boolean $unixTime 是否返回时间戳
     *
     * @return string|int
     */
    public function getPayTime($unixTime = false){
        return $unixTime ? $this->AR->pay_unixtime : $this->AR->pay_datetime;
    }

    /**
     * 获取用户ID
     *
     * @return int
     */
    public function getUserId(){
        return $this->AR->custom_user_id;
    }
}
