<?php
namespace custom\models\parts\trade;

use common\ActiveRecord\CustomUserReceiveLogAR;
use common\ActiveRecord\SupplyUserPayRefundAR;
use common\models\parts\order\OrderRefund;
use yii\base\InvalidCallException;
use yii\base\Object;
use Yii;

class ReceiveLog extends Object {

    public $id;

    protected $AR;

    public function init(){
        if(!$this->id ||
            !$this->AR = CustomUserReceiveLogAR::findOne(['id'=>$this->id]))throw new InvalidCallException;
    }

    /**
     *====================================================
     * 获取入账类型
     * @return mixed
     * @author shuang.li
     * @Date:2017年3月28日
     *====================================================
     */
    public function getReceiveType(){
        return $this->AR->receive_type;
    }


    /**
     *====================================================
     * 获取对应支付记录id
     * @return mixed
     * @author shuang.li
     * @Date:2017年3月28日
     *====================================================
     */
    public function getLogId(){
        return $this->AR->corresponding_log_id;
    }

    /**
     *====================================================
     * 获取接收金额
     * @return mixed
     * @author shuang.li
     * @Date:2017年3月28日
     *====================================================
     */
    public function getReceiveAmount(){
        return $this->AR->receive_amount;
    }


    public function getRefund(){
        $id = Yii::$app->RQ->AR(new SupplyUserPayRefundAR)->scalar([
            'select'=>['order_refund_id'],
            'where'=>[
                'supply_user_pay_log_id'=>$this->AR->corresponding_log_id
            ],
            'limit'=>1,
        ]);

        return new OrderRefund([
            'id'=>$id
        ]);
    }


}
