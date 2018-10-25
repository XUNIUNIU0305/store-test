<?php
namespace custom\models\parts\trade;

use common\ActiveRecord\CustomUserTradeMembraneAR;
use common\ActiveRecord\MembraneOrderAR;
use common\models\parts\coupon\CouponRecord;
use common\models\parts\custom\CustomUser;
use common\models\parts\MembraneOrder;
use Yii;
use common\models\parts\trade\TradeAbstract;
use common\ActiveRecord\CustomUserTradeAR;
use common\models\parts\Order;
use common\models\RapidQuery;
use common\ActiveRecord\CustomUserTradeOrderAR;
use common\ActiveRecord\CustomUserTradeZodiacAR;
use custom\modules\temp\models\parts\Zodiac;
use common\ActiveRecord\TempYougaZodiacNumberAR;

class Trade extends TradeAbstract{

    const TYPE_ORDER = 1;
    const TYPE_ZODIAC = 2;
    const TYPE_MEMBRANE = 3;    //膜订单
    const TYPE_GPUBS = 4;

    //是否已发放优惠券
    const COUPON_SEND_TYPE_NO=0;//未发放
    const COUPON_SEND_TYPE_YES=1;//已发放

    protected function getActiveRecord(){
        return new CustomUserTradeAR;
    }

    /**
     * 获取该交易单所属的用户ID
     *
     * @return int
     */
    public function getUserId(){
        return $this->AR->custom_user_id;
    }

    //获取用户信息
    public function getCustomer(){
        return new CustomUser(['id'=>$this->getUserId()]);
    }

    public function getGpubsTickets(){
        if($this->type != self::TYPE_GPUBS)return false;
        if(!$ticketIds = Yii::$app->RQ->AR(new \common\ActiveRecord\CustomUserTradeGpubsAR)->column([
            'select' => ['activity_gpubs_group_ticket_id'],
            'where' => [
                'custom_user_trade_id' => $this->AR->id,
            ],
        ]))return false;
        return array_map(function($ticketId){
            return new \common\models\parts\gpubs\GpubsGroupTicket([
                'id' => $ticketId,
            ]);
        }, $ticketIds);
    }

    /**
     * 获取交易单的所有订单
     *
     * @return array
     */
    public function getOrders(){
        if($this->type != self::TYPE_ORDER)return false;
        if(!$ordersId = (new RapidQuery(new CustomUserTradeOrderAR))->column([
            'select' => ['order_id'],
            'where' => ['custom_user_trade_id' => $this->AR->id],
        ]))return false;
        return array_map(function($orderId){
            return new Order(['id' => $orderId]);
        }, $ordersId);
    }

    public function getZodiac(){
        if($this->type != self::TYPE_ZODIAC)return false;
        return new Zodiac([
            'selectedNumber' => Yii::$app->RQ->AR(new CustomUserTradeZodiacAR)->column(
                [
                    'select' => ['temp_youga_zodiac_number_id'],
                    'where' => [
                        'custom_user_trade_id' => $this->id,
                    ],
                ]
            ),
        ]);
    }

    //获取当前用户选中的星座👌
    public function getZodiacNumber()
    {

        if ($this->type != self::TYPE_ZODIAC)
        {
            return false;
        }
        $numberIds = Yii::$app->RQ->AR(new CustomUserTradeZodiacAR)->column([
                'select' => ['temp_youga_zodiac_number_id'],
                'where' => [
                    'custom_user_trade_id' => $this->id,
                ],
            ]);


        return  Yii::$app->RQ->AR(new TempYougaZodiacNumberAR)->all([
            'select' => [
                'temp_youga_zodiac_id',
                'num'
            ],
            'where' => [
                'id' => $numberIds,
                'selected' => Zodiac::STATUS_SELECTED,
                'custom_user_id' => $this->userId,
            ],
        ]);

    }

    /**
     * 获取膜订单
     * @return MembraneOrder[]
     */
    public function getMembraneOrders()
    {
        $rel = Yii::$app->RQ->AR(new CustomUserTradeMembraneAR)->column([
            'select' => ['membrane_order_id'],
            'where' => ['custom_user_trade_id' => $this->AR->id]
        ]);
        $ars = MembraneOrderAR::find()
            ->where(['id' => $rel])
            ->all();
        return array_map(function($ar){
            return new MembraneOrder(['AR' => $ar]);
        }, $ars);
    }

    /**
     * 获取总金额
     *
     * @return float
     */
    public function getTotalFee(){
        return (float)$this->AR->total_fee;
    }

    /**
     * 获取支付方式
     *
     * @return int
     */
    public function getPaymentMethod(){
        return $this->AR->payment_method;
    }

    /**
     * 返回该交易单充值记录ID
     * 如果交易单使用在线支付则返回ID，否则返回0
     *
     * @return int
     */
    public function getRechargeId(){
        return $this->AR->custom_user_recharge_log_id;
    }

    /**
     * 设置充值记录ID
     *
     * @return int|false
     */
    public function setRechargeId($rechargeId){
        $this->AR->custom_user_recharge_log_id = $rechargeId;
        return $this->AR->update();
    }

    /**
     * 获取交易单状态
     *
     * @return int
     */
    public function getStatus(){
        return $this->AR->status;
    }

    public function getType(){
        return $this->AR->type;
    }

    /**
     * 设置交易单状态为已支付
     * 同时设置相关订单状态为已支付（未发货）
     * 或者修改星座数字为已选择
     *
     * @return boolean
     */
    public function setPaid(int $rechargeId = 0){
        if($rechargeId < 0)return false;
        if($this->status == self::STATUS_UNPAID && !$this->needRecharge){
            $transaction = Yii::$app->db->beginTransaction();
            try{
                if($this->type == self::TYPE_ORDER){
                    if(!$this->changeOrdersStatus())throw new \Exception;
                }elseif($this->type == self::TYPE_ZODIAC){
                    if(!$this->changeZodiacSelected())throw new \Exception;
                }elseif($this->type === self::TYPE_MEMBRANE){
                    if(!$this->changeMembraneStatus())throw new \Exception;
                }elseif($this->type == self::TYPE_GPUBS){
                    if(!$this->changeGpubsStatus())throw new \Exception;
                }
                if(!(new RapidQuery($this->AR))->update([
                    'custom_user_recharge_log_id' => $rechargeId ? : $this->rechargeId,
                    'status' => self::STATUS_PAID,
                    'pay_datetime' => Yii::$app->time->fullDate,
                    'pay_unixtime' => Yii::$app->time->unixTime,
                ]))throw new \Exception;
                $transaction->commit();
                return true;
            }catch(\Exception $e){
                $transaction->rollBack();
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 获取创建时间
     *
     * @param boolean $unixTime 是否返回时间戳
     *
     * @return string|int
     */
    public function getCreateTime($unixTime = false){
        return $unixTime ? $this->AR->create_unixtime : $this->AR->create_datetime;
    }

    /**
     * 检查是否需要充值
     *
     * @return boolean
     */
    public function getNeedRecharge(){
        return ($this->paymentMethod != PaymentMethod::METHOD_BALANCE && !$this->rechargeId);
    }

    /**
     * 检查是否已充值
     *
     * @return boolean
     */
    public function getHasRecharged(){
        return $this->rechargeId ? true : false;
    }

    /**
     * 获取交易单所有状态
     *
     * @return array
     */
    public static function getStatuses(){
        return [
            self::STATUS_UNPAID,
            self::STATUS_PAID,
        ];
    }

    protected function changeGpubsStatus(){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(!$tickets = $this->getGpubsTickets())throw new \Exception;
            foreach($tickets as $ticket){
                $ticket->setStatus(\common\models\parts\gpubs\GpubsGroupTicket::STATUS_PAID);
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 更改该交易单下所有订单的状态为已支付（未发货）
     *
     * @return boolean
     */
    protected function changeOrdersStatus(){
        $orders = $this->orders;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($orders as $order){
                if(!$order->setStatus($order::STATUS_UNDELIVER))throw new \Exception;
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    protected function changeZodiacSelected(){
        return TempYougaZodiacNumberAR::updateAll([
            'selected' => Zodiac::STATUS_SELECTED,
            'custom_user_id' => $this->userId,
            'selected_datetime' => Yii::$app->time->fullDate,
            'selected_unixtime' => Yii::$app->time->unixTime,
        ], [
            'id' => $this->zodiac->number,
        ]);
    }

    /**
     * 订单更新为已支付
     * @return bool
     */
    protected function changeMembraneStatus()
    {
        $orders = $this->getMembraneOrders();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($orders as $order)
                $order->toPayed();
            $transaction->commit();
            return true;
        } catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    //获取使用优惠券 ID
    public function getCouponRecord(){
        return $this->AR->coupon_record_id?new CouponRecord(['id'=>$this->AR->coupon_record_id]):false;
    }

    //设置优惠券发放状态
    public function setCouponSendStatus($status){
        if(!in_array($status,[self::COUPON_SEND_TYPE_NO,self::COUPON_SEND_TYPE_YES])){
            return false;
        }
        $this->AR->coupon_send_status=$status;
        return $this->AR->save();
    }

    //获取发放状态
    public function getCouponSendStatus(){
        return $this->AR->coupon_send_status;
    }


}
