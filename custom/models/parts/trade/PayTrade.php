<?php
namespace custom\models\parts\trade;

use common\ActiveRecord\CustomUserPayTradeAR;
use yii\base\InvalidCallException;
use yii\base\Object;

class PayTrade extends Object {

    public $log_id;

    protected $AR;

    public function init(){
        if(!$this->log_id ||
            !$this->AR = CustomUserPayTradeAR::findOne(['custom_user_pay_log_id'=>$this->log_id]))throw new InvalidCallException;
    }

    /**
     *
     * @return int
     */
    public function getTradeId(){
        return $this->AR->custom_user_trade_id;
    }

}
