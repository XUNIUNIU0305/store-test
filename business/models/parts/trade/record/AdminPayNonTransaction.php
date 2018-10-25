<?php
/**
 * Created by PhpStorm.
 * User: tangzhaofeng
 * Date: 18-7-25
 * Time: 上午10:53
 */

namespace business\models\parts\trade\record;

use common\ActiveRecord\AdminPayNonTransactionAR;
use yii\base\InvalidCallException;
use yii\base\Object;

class AdminPayNonTransaction extends Object {

    public $log_id;

    protected $AR;

    public function init(){
        if(!$this->log_id ||
            !$this->AR = AdminPayNonTransactionAR::findOne(['admin_pay_log_id'=>$this->log_id]))throw new InvalidCallException;
    }

    /**
     *
     * @return int
     */
    public function getDepositId(){
        return $this->AR->non_transaction_deposit_and_draw_id;
    }

}