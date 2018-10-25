<?php
namespace common\models\parts\trade;

use Yii;
use yii\base\Object;
use yii\base\InvalidCallException;

abstract class WalletAbstract extends Object{

    /**
     * 收款原因：
     * 未定义
     * 充值
     * 用户消费
     * 交易完成（Customer确认收货）
     */
    const RECEIVE_UNDEFINED = 0;
    const RECEIVE_RECHARGE = 1; //Outer => Customer 充值
    const RECEIVE_CUSTOM_CONSUMPTION = 2; //Customer => Admin 用户消费
    const RECEIVE_ORDER_CONFIRMED = 3; //Admin => Supplier 订单确认收货
    const RECEIVE_ORDER_CANCELED = 4; //Admin => Customer 取消已支付订单
    //Add JiangYi Date:2017/04/10  Desc:客户申请退货后，商家退换
    const RECEIVE_SUPPLY_REFUND=5;//Supply=>Customer 客户申请退货，商家退换
    const RECEIVE_PARTNER_RECHARGE = 6; //Outer => Admin 门店加盟充值
    const RECEIVE_PARTNER_AWARD = 7; //Admin => Business 门店加盟奖励
    const RECEIVE_MEMBRANE_ORDER_CANCELED = 8; //Admin => Customer 膜订单取消
    const RECEIVE_MEMBRANE_ORDER_FINISH = 9; //Admin => Business 膜订单完成
    const RECEIVE_BUSINESS_DRAW = 11; //Business => Admin2 B站提现
    const RECEIVE_VOUCHER = 12; //代金券充值
    const RECEIVE_GROUPBUY = 13; //团购返现收款　admin => custom
    const RECEIVE_NON_TRANSACTION = 14; //非交易入账 admin <=> business|custom|supplier
    const RECEIVE_GPUBS_ORDER = 15; //自提团购退款 admin => custom

    const PAY_UNDEFINED = 60;
    const PAY_ORDER_CONFIRMED = 61; //Admin => Supplier 订单确认收货
    const PAY_TRADE = 62; //Customer => Admin 支付交易单
    const PAY_ORDER_CANCELED = 63; //Admin => Customer 取消已支付订单
    //Add JiangYi Date:2017/04/10  Desc:商户退款，客户退款
    const PAY_ORDER_REFUND=64;//Supply=>Customer 商户退款
    const PAY_PARTNER_AWARD = 65; //Admin => Business|Customer 门店加盟奖励
    const PAY_MEMBRANE_ORDER_CANCELED = 66; //Admin => Customer 取消已支付膜订单
    const PAY_MEMBRANE_ORDER_FINISH = 67; //Admin => Business 膜订单完成
    const PAY_BUSINESS_DRAW = 69; //Business => Admin2 B站提现
    const PAY_PRIZE = 70; //支付代金券　admin => custom
    const PAY_GROUPBUY = 71; //团购返现　admin => custom table:activity_groupbuy_log
    const PAY_NON_TRANSACTION = 72; //非交易出账 admin <=> business|custom|supplier
    const PAY_GPUBS_ORDER = 73; //自提团购退款 admin => custom

    const FREEZE_BUSINESS_DRAW = 10; //BUSINESS 出金 资金冻结
    const THAW_BUSINESS_DRAW = 68; //BUSINESS 出金 资金解冻

    //钱包表的主键
    public $id;

    public $receiveType = self::RECEIVE_UNDEFINED;

    protected $AR;

    public function init(){
        $ActiveRecord = $this->ActiveRecord;
        $tableName = $ActiveRecord::tableName();
        $result = Yii::$app->db->createCommand("SELECT * FROM {$tableName} WHERE [[id]] = :id FOR UPDATE")->bindValues([':id' => $this->id])->queryOne();
        if(!$this->id ||
            !$this->AR = $ActiveRecord::findOne($this->id))throw new InvalidCallException;
    }

    abstract protected function getActiveRecord();

    /**
     * 支付
     *
     * @param mix $something 支付对象
     * @param Object $receiver 收款方
     *
     * @return boolean
     */
    abstract public function pay($something, WalletAbstract $receiver = null);

    /**
     * 收款
     *
     * @param mix $something 款项信息
     *
     * @return boolean
     */
    abstract public function receive($something);

    /**
     * 获取可以接受的收款类型
     *
     * @return array
     */
    abstract public static function getReceiveTypes();

    /**
     * 扣款
     *
     * @param float $rmb 金额
     *
     * @return boolean
     */
    protected function decreaseRMB(float $rmb){
        //return $this->AR->updateCounters([
            //'rmb' => $rmb * - 1,
        //]);
        $this->AR->rmb = $this->AR->rmb - $rmb;
        return $this->AR->update() === false ? false : true;
    }

    /**
     * 入款
     *
     * @param float $rmb 金额
     *
     * @return boolean
     */
    protected function increaseRMB(float $rmb){
        //return $this->AR->updateCounters([
            //'rmb' => $rmb,
        //]);
        $this->AR->rmb = $this->AR->rmb + $rmb;
        return $this->AR->update() === false ? false : true;
    }
}
