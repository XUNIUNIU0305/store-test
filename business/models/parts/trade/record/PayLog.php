<?php


namespace business\models\parts\trade\record;

use common\ActiveRecord\BusinessUserPayLogAR;
use yii\base\InvalidCallException;
use yii\base\Object;

class PayLog extends Object {

    public $id;

    protected $AR;

    public function init(){
        if(!$this->id ||
            !$this->AR = BusinessUserPayLogAR::findOne(['id'=>$this->id]))throw new InvalidCallException;
    }

    //获取入账类型
    public function getPayType(){
        return $this->AR->pay_type;
    }
    //获取对应支付记录id
    public function getLogId(){
        return $this->AR->id;
    }


    //获取接收金额
    public function getPayAmount(){
        return $this->AR->pay_amount;
    }
}
