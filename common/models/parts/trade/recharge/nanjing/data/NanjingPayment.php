<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//资金支付
class NanjingPayment extends Base{

    public function getAttrs() : array{
        return [
            'MerchantId' => true,
            'MerUserId' => true,
            'MerUserAcctNo' => true,
            'MerSellerId' => true,
            'MerSellerAcctNo' => true,
            'VerCode' => false,
            'VerSeqNo' => false,
            'MerchantSeqNo' => true,
            'MerchantDateTime' => true,
            'OperType' => true,
            'OrgMerchantSeqNo' => false,
            'PayDevide' => true,
            'FeeAmount' => false,
            'TransAmount' => true,
            'Currency' => false,
            'OrderNo' => true,
            'ProductInfo' => true,
            'Remark1' => false,
            'Remark2' => false,
        ];
    }

    public function getOperation() : string{
        return self::OPERATION_PAYMENT;
    }
}
