<?php
namespace custom\models\parts\trade;

use common\ActiveRecord\AlipayNotifyLogAR;
use yii\base\InvalidCallException;
use yii\base\Object;

class NotifyLog extends Object {

    public $id;

    protected $AR;

    public function init(){
        if(!$this->id ||
            !$this->AR = AlipayNotifyLogAR::findOne(['id'=>$this->id]))throw new InvalidCallException;
    }

    /**
     *
     * @return int
     */
    public function getTradeNo(){
        return $this->AR->trade_no;
    }

}
