<?php
namespace custom\models\parts\trade;

use common\ActiveRecord\CustomUserReceiveLogAR;
use common\ActiveRecord\CustomUserRechargeLogAR;
use yii\base\InvalidCallException;
use yii\base\Object;

class RechargeLog extends Object
{
    const RECHANGE_METHOD_ALIPAY = 2;
    const RECHANGE_METHOD_WECHAT = 3;
    const RECHANGE_METHOD_GATEWAY_PERSON = 4;
    const RECHANGE_METHOD_GATEWAY_CORP = 5;
    const RECHANGE_METHOD_ABCHINA = 6;

    public $id;

    protected $AR;

    public function init()
    {
        if (!$this->id || !$this->AR = CustomUserRechargeLogAR::findOne(['id' => $this->id]))
        {
            throw new InvalidCallException;
        }
    }

    /**
     *====================================================
     * 获取支付方式记录id
     * @return mixed
     * @author shuang.li
     * @Date:2017年3月28日
     *====================================================
     */
    public function getNotifyId()
    {
        return $this->AR->corresponding_notify_id;
    }


    /**
     *====================================================
     * 获取充值金额
     * @return mixed
     * @author shuang.li
     * @Date:2017年3月28日
     *====================================================
     */
    public function getRechargeAmount()
    {
        return $this->AR->recharge_amount;
    }

    /**
     *====================================================
     * 获取充值类型
     * @return mixed
     * @author shuang.li
     * @date 2017年6月16日
     *====================================================
     */
    public function getRechargeMethod(){
        return $this->AR->recharge_method;
    }


    /**
     *====================================================
     * 获取充值交易单号
     * @return int
     * @author shuang.li
     * @Date:
     *====================================================
     */
    public function getTradeNo()
    {
        switch ($this->AR->recharge_method)
        {
        case self::RECHANGE_METHOD_ALIPAY:
            $notifyLog = new NotifyLog([
                'id' => $this->AR->corresponding_notify_id,
            ]);
            break;
        case self::RECHANGE_METHOD_WECHAT:
            $notifyLog = new WxNotifyLog([
                'id' => $this->AR->corresponding_notify_id,
            ]);
            break;

        case self::RECHANGE_METHOD_GATEWAY_PERSON:
        case self::RECHANGE_METHOD_GATEWAY_CORP:
            try{
                $rechargeId = \common\ActiveRecord\NanjingGatewayDepositAR::findOne($this->AR->corresponding_notify_id);
                return \common\ActiveRecord\RechargeApplyAR::findOne($rechargeId)->recharge_number;
            }catch(\Exception $e){
                return '';
            }
            break;

        case self::RECHANGE_METHOD_ABCHINA:
            return \Yii::$app->RQ->AR(new \common\ActiveRecord\AbchinaNotifyLogAR)->scalar([
                'select' => ['recharge_number'],
                'where' => [
                    'id' => $this->AR->corresponding_notify_id,
                ],
            ]);
            break;

        }
        return $notifyLog->getTradeNo();
    }


}
