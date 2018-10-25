<?php
/**
 * Created by PhpStorm.
 * User: tangzhaofeng
 * Date: 18-7-24
 * Time: 上午10:27
 */

namespace business\models\parts\trade\record;

use common\ActiveRecord\BusinessUserReceiveLogAR;
use yii\base\InvalidCallException;
use yii\base\Object;

class ReceiveLog extends Object {

    public $id;

    protected $AR;

    public function init(){
        if(!$this->id ||
            !$this->AR = BusinessUserReceiveLogAR::findOne(['id'=>$this->id]))throw new InvalidCallException;
    }

     //获取入账类型
    public function getReceiveType(){
        return $this->AR->receive_type;
    }
     //获取对应支付记录id
    public function getLogId(){
        return $this->AR->corresponding_log_id;
    }


     //获取接收金额
    public function getReceiveAmount(){
        return $this->AR->receive_amount;
    }
}
