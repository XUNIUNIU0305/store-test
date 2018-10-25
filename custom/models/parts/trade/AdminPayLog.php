<?php
namespace custom\models\parts\trade;

use common\ActiveRecord\AdminPayLogAR;
use common\models\parts\trade\PayLogAbstract;
use yii\base\InvalidCallException;

class AdminPayLog extends PayLogAbstract{

    //admin_pay_log表主键
    public $id;

    protected $AR;

    public function init(){
        if(!$this->id ||
            !$this->AR = AdminPayLogAR::findOne($this->id))throw new InvalidCallException;
    }

    /**
     * 获取出账金额
     *
     * @return float
     */
    public function getPayAmount(){
        return $this->AR->pay_amount;
    }

    /**
     * 获取出账前余额
     *
     * @return float
     */
    public function getRMBBefore(){
        return (float)$this->AR->rmb_before;
    }

    /**
     * 获取出账后余额
     *
     * @return float
     */
    public function getRMBAfter(){
        return (float)$this->AR->rmb_after;
    }

    /**
     * 获取出账时间
     *
     * @param boolean $unixTime 是否返回时间戳
     *
     * @return string|int
     */
    public function getPayTime($unixTime = false){
        return $unixTime ? $this->AR->pay_unixtime : $this->AR->pay_datetime;
    }

    /**
     * 获取钱包ID
     *
     * @return int
     */
    public function getWalletId(){
        return $this->AR->admin_wallet_id;
    }
}
