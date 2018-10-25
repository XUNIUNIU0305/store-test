<?php
namespace common\models\parts\partner;

use common\ActiveRecord\AdminRechargeApplyAR;
use common\ActiveRecord\AdminRechargeLogAR;
use common\ActiveRecord\AdminTradePartnerAR;
use common\ActiveRecord\AlipayNotifyLogAR;
use common\ActiveRecord\WxpayNotifyLogAR;
use custom\models\parts\trade\RechargeLog;
use Yii;
use yii\base\Object;
use common\ActiveRecord\PartnerApplyAR;
use yii\base\InvalidConfigException;
use custom\components\handler\RegistercodeHandler;
use admin\models\parts\sms\SmsCaptcha;
use common\models\parts\sms\SmsSender;
use custom\models\parts\RegisterCode;
use custom\components\handler\AccountHandler;

class PartnerApply extends Object{

    const APPLY_RMB = 1;
    const AWARD_RMB = 0;

    public $id;
    protected $AR;

    public function init(){
        if(!$this->AR = PartnerApplyAR::findOne($this->id))throw new InvalidConfigException('unavailable id');
    }

    public function getPartnerPromoter(){
        return new PartnerPromoter(['id' => $this->AR->partner_promoter_id]);
    }

    public function getMobile(){
        return $this->AR->mobile ? : '';
    }

    public function getPasswd(){
        return $this->AR->passwd ? : '';
    }

    public function getCreateTime(bool $unixTime = false){
        return $unixTime ? $this->AR->create_unixtime : $this->AR->create_datetime;
    }

    public function getPayTime(bool $unixTime = false){
        if($unixTime){
            return $this->AR->pay_unixtime ? : false;
        }else{
            return ($this->AR->pay_datetime == '0000-01-01 00:00:00' ? false : $this->AR->pay_datetime);
        }
    }

    public function getIsPaid(){
        return $this->AR->pay_unixtime ? true : false;
    }

    public function getAwardRmb(){
        return (float)$this->AR->award_rmb;
    }

    public function setPaid($return = 'throw'){
        if($this->getIsPaid())return Yii::$app->EC->callback($return, 'this apply has been paid');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $registerCode = current(RegistercodeHandler::createPartnerCode(1));
            Yii::$app->RQ->AR($this->AR)->update([
                'pay_datetime' => Yii::$app->time->fullDate,
                'pay_unixtime' => Yii::$app->time->unixTime,
                'custom_user_registercode_id' => $registerCode->id,
            ]);
            //取消发送注册码，直接注册
            if($this->getPasswd()){
                $account = AccountHandler::create([
                    'account' => $registerCode,
                    'passwd' => $this->getPasswd(),
                    'district' => new \common\models\parts\district\District(['districtId' => 721]), //默认上海 宝山
                    'mobile' => $this->getMobile(),
                    'email' => $registerCode->account . '@unknown',
                ]);
                $account->passwd = $this->getPasswd();
                $account->save();
                $couponSender = new \custom\models\parts\temp\SendCouponAfterRegister\CouponSender;
                $couponSender->sendTo($account->account);
            }else{
                $this->sendRegisterCode($registerCode->account);
            }
            //直接注册结束
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public function getRegisterCode(){
        if(!$this->getIsPaid())return false;
        return new RegisterCode(['id' => $this->AR->custom_user_registercode_id]);
    }

    protected function sendRegisterCode($registerCode){
        $message = new \common\components\amqp\Message(new SendRegistercode([
            'mobile' => $this->mobile,
            'registerCode' => $registerCode,
        ]));
        Yii::$app->amqp->publish($message);
        return true;
    }


    /**
     *====================================================
     * 设置退款单号
     * @param        $refundNumber
     * @param string $return
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function setRefundNumber($refundNumber,$return = 'throw'){
        return Yii::$app->RQ->AR($this->AR)->update(['refund_number' =>$refundNumber], $return);
    }

    public function getRefundNumber(){
        return $this->AR->refund_number;
    }

    /**
     *====================================================
     * 设置注销
     * @param string $return
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function setCancel($return = 'throw') {
        return Yii::$app->RQ->AR($this->AR)->update(['is_cancel' =>1], $return);
    }


    /**
     * 获取支付信息
     */

    public function getPay(){
        $adminTradeId = Yii::$app->RQ->AR(new AdminTradePartnerAR())->scalar([
            'select'=>['admin_trade_id'],
            'where'=>['partner_apply_id'=>$this->id]
        ]);

        $id = Yii::$app->RQ->AR(new AdminRechargeApplyAR())->scalar([
            'select'=>['id'],
            'where'=>['admin_trade_id'=>$adminTradeId]
        ]);

        $rechargeLog = Yii::$app->RQ->AR(new AdminRechargeLogAR())->one([
            'select'=>['corresponding_notify_id','recharge_method'],
            'where'=>[
                'admin_recharge_apply_id'=>$id
            ],
        ]);
        $res = '';
        switch ($rechargeLog['recharge_method']) {
        case RechargeLog::RECHANGE_METHOD_WECHAT:
            $res = Yii::$app->RQ->AR(new WxpayNotifyLogAR())->one([
                'select'=>['transaction_id','out_trade_no'],
                'where'=>['id'=>$rechargeLog['corresponding_notify_id']]
            ]);
            break;
        case RechargeLog::RECHANGE_METHOD_ALIPAY:
            $res = Yii::$app->RQ->AR(new AlipayNotifyLogAR())->one([
                'where'=>['id'=>$rechargeLog['corresponding_notify_id']]
            ]);
        }
        return $res;

    }
}
