<?php
namespace custom\models\parts\trade;

use Yii;
use common\models\parts\trade\PaymentMethodInterface;

class PaymentMethod extends RechargeMethod implements PaymentMethodInterface{

    public function init(){
        if(self::canPay($this->method)){
            $this->_method = $this->method;
        }
    }

    /**
     * 获取实例化对象设置的当前支付方式
     *
     * @return int|null
     */
    public function getCurrentPaymentMethod(){
        return $this->_method;
    }

    /**
     * 获取实例化对象设置的当前充值方式
     *
     * 如果支付方式为余额支付则返回NULL
     *
     * @return int|null
     */
    public function getCurrentRechargeMethod(){
        return self::canRecharge($this->_method) ? $this->_method : null;
    }

    /**
     * 获取支付方式列表
     *
     * @return array
     */
    public static function getPaymentMethods(){
        return array_merge(self::getRechargeMethods(), [
            self::METHOD_BALANCE,
        ]);
    }

    /**
     * 验证是否能使用$method支付
     *
     * 如果是余额支付且指定$rmb，则验证钱包余额是否足够支付该金额
     *
     * @param int $method 需要验证的支付方式
     * @param float $rmb 支付金额
     *
     * @return boolean
     */
    public static function canPay($method, float $rmb = null){
        if(in_array($method, self::getPaymentMethods())){
            if($method == self::METHOD_BALANCE && !is_null($rmb)){
                if(Yii::$app->user->isGuest)return false;
                Yii::$app->RQ->queryMaster = true;
                $walletRMB = Yii::$app->CustomUser->wallet->RMB;
                Yii::$app->RQ->queryMaster = false;
                return $walletRMB >= $rmb;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
}
