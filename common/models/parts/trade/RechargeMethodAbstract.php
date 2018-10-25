<?php
namespace common\models\parts\trade;

use Yii;
use yii\base\Object;

abstract class RechargeMethodAbstract extends Object{

    //支付宝支付
    const METHOD_ALIPAY = 2;

    //微信浏览器内密码支付
    const METHOD_WX_INWECHAT = 3;

    //银行网关支付-个人卡
    const METHOD_GATEWAY_PERSON = 4;

    //银行网关支付-企业账户
    const METHOD_GATEWAY_CORP = 5;

    //农行网关支付
    const METHOD_ABCHINA_GATEWAY = 6;

    /**
     * 获取充值方法列表
     *
     * @return array
     */
    abstract public static function getRechargeMethods();

    /**
     * $method是否支持充值
     *
     * @return boolean
     */
    public static function canRecharge($method){
        return in_array($method, static::getRechargeMethods());
    }
}
