<?php
namespace common\models\parts\trade;

use Yii;
use yii\base\Object;
use yii\base\InvalidCallException;

abstract class TradeAbstract extends Object{

    //未支付
    const STATUS_UNPAID = 0;
    //已支付
    const STATUS_PAID = 1;

    //Trade表主键
    public $id;

    //Object ActiveRecord
    protected $AR;

    public function init(){
        if(!$this->id)throw new InvalidCallException;
        $ActiveRecord = $this->ActiveRecord;
        if(!$this->AR = $ActiveRecord::findOne($this->id))throw new InvalidCallException;
    }

    abstract protected function getActiveRecord();

    /**
     * 获取该交易的用户ID
     *
     * @return int
     */
    abstract public function getUserId();

    /**
     * 获取交易的订单
     *
     * @return array
     */
    abstract public function getOrders();

    /**
     * 获取交易的总金额
     *
     * @return float
     */
    abstract public function getTotalFee();

    /**
     * 获取交易的支付方式
     *
     * @return int
     */
    abstract public function getPaymentMethod();

    /**
     * 获取交易的当前状态：未支付 已支付
     *
     * @return int
     */
    abstract public function getStatus();

    /**
     * 获取交易创建时间
     *
     * @return string|int
     */
    abstract public function getCreateTime();

    /**
     * 获取交易状态列表
     *
     * @return array
     */
    abstract public static function getStatuses();
}
