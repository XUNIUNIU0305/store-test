<?php
namespace admin\models\parts\trade;

use Yii;
use yii\base\Object;
use common\ActiveRecord\AdminTradeAR;
use common\ActiveRecord\AdminTradePartnerAR;
use common\models\parts\trade\TradeAbstract;
use common\models\parts\partner\PartnerApply;
use common\models\parts\trade\PaymentMethodInterface;

class Trade extends TradeAbstract{

    const TYPE_PARTNER = 3;

    public $id;
    protected $AR;

    protected function getActiveRecord(){
        return new AdminTradeAR;
    }

    public function getUserId(){
        return false;
    }

    public function getOrders(){
        return false;
    }

    public function getType(){
        return $this->AR->type;
    }

    public function getTotalFee(){
        return (float)$this->AR->total_fee;
    }

    public function getPaymentMethod(){
        return $this->AR->payment_method;
    }

    public function getRechargeId(){
        return $this->AR->admin_recharge_log_id;
    }

    public function setRechargeId($rechargeId, $return = 'throw'){
        return Yii::$app->RQ->AR($this->AR)->update([
            'admin_recharge_log_id' => $rechargeId,
        ], $return);
    }

    public function getStatus(){
        return $this->AR->status;
    }

    public static function getStatuses(){
        return [
            self::STATUS_UNPAID,
            self::STATUS_PAID,
        ];
    }

    public function getCreateTime(bool $unixTime = false){
        return $unixTime ? $this->AR->create_unixtime : $this->AR->create_datetime;
    }

    public function getPayTime(bool $unixTime = false){
        if(!$this->AR->pay_unixtime)return false;
        return $unixTime ? $this->AR->pay_unixtime : $this->AR->pay_datetime;
    }

    public function getNeedRecharge(){
        return ($this->paymentMethod != PaymentMethodInterface::METHOD_BALANCE && !$this->getRechargeId());
    }

    public function getHasRecharged(){
        return $this->getRechargeId() ? true : false;
    }

    public function getIsPaid(){
        return $this->AR->pay_unixtime ? true : false;
    }

    public function setPaid($return = 'throw'){
        if($this->getIsPaid())return Yii::$app->EC->callback($return, 'this trade has been paid');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            Yii::$app->RQ->AR($this->AR)->update([
                'status' => self::STATUS_PAID,
                'pay_datetime' => Yii::$app->time->fullDate,
                'pay_unixtime' => Yii::$app->time->unixTime,
            ]);
            if($this->getType() == self::TYPE_PARTNER){
                $this->partnerApply->setPaid();
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public function getPartnerApply(){
        if($this->getType() != self::TYPE_PARTNER)return false;
        $applyId = AdminTradePartnerAR::findOne(['admin_trade_id' => $this->id])->partner_apply_id;
        return new PartnerApply(['id' => $applyId]);
    }
}
