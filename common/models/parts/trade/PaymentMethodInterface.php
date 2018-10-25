<?php
namespace common\models\parts\trade;

interface PaymentMethodInterface{

    //余额支付
    const METHOD_BALANCE = 1;

    /**
     * 获取全部支付方法
     *
     * @return array
     */
    public static function getPaymentMethods();

    /**
     * $method是否支持支付
     *
     * @return boolean
     */
    public static function canPay($method);
}
