<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//网关支付
class NanjingGatewayPayment extends Base{

    public function getAttrs() : array{
        return [
            'MerchantId' => true,
            'MerchantSeqNo' => true,
            'MerchantDateTime' => true,
            'OperType' => true,
            'AcctType' => true,
            'MerUserId' => true,
            'MerUserAcctNo' => true,
            'MerSellerId' => false,
            'MerSellerAcctNo' => false,
            'Currency' => false,
            'TransAmount' => true,
            'FeeAmount' => false,
            'OrderNo' => false,
            'PayDevide' => false,
            'ProductInfo' => false,
            'Remark1' => false,
            'Remark2' => false,
        ];
    }

    public function getOperation() : string{
        return self::OPERATION_GATEWAY_PAYMENT;
    }
}
