<?php
namespace business\models\parts\trade\record;

use common\ActiveRecord\AdminPayMembraneOrderAR;
use yii\base\InvalidCallException;
use yii\base\Object;

class AdminPayMembraneOrder extends Object {

    public $log_id;

    protected $AR;

    public function init(){
        if(!$this->log_id ||
            !$this->AR = AdminPayMembraneOrderAR::findOne(['admin_pay_log_id'=>$this->log_id]))throw new InvalidCallException;
    }

    /**
     *
     * @return int
     */
    public function getMembraneOrderId(){
        return $this->AR->membrane_order_id;
    }

}
