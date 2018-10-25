<?php
namespace custom\models\parts\trade;

use common\ActiveRecord\AdminPayOrderAR;
use yii\base\InvalidCallException;
use yii\base\Object;

class AdminPayOrder extends Object {

    public $log_id;

    protected $AR;

    public function init(){
        if(!$this->log_id ||
            !$this->AR = AdminPayOrderAR::findOne(['admin_pay_log_id'=>$this->log_id]))throw new InvalidCallException;
    }

    /**
     *
     * @return int
     */
    public function getOrderId(){
        return $this->AR->order_id;
    }

}
