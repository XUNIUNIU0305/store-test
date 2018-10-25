<?php
namespace custom\models\parts\trade;

use common\ActiveRecord\WxpayNotifyLogAR;
use yii\base\InvalidCallException;
use yii\base\Object;

class WxNotifyLog extends Object {

    public $id;

    protected $AR;

    public function init(){
        if(!$this->id ||
            !$this->AR = WxpayNotifyLogAR::findOne(['id'=>$this->id]))throw new InvalidCallException;
    }

    /**
     *
     * @return int
     */
    public function getTradeNo(){
        return $this->AR->transaction_id;
    }

}
