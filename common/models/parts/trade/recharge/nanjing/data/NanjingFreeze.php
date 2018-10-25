<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//资金冻结（担保交易功能）
class NanjingFreeze extends Base{

    public function getAttrs() : array{
        return [
            'MerchantId' => true,
            'MerUserId' => true,
            'MerSellerId' => true,
            'MerchantSeqNo' => true,
            'MerchantDateTime' => true,
            'Direction' => true,
            'MerUserAcctNo' => true,
            'MerSellerAcctNo' => true,
            'Currency' => false,
            'TransAmount1' => false,
            'TransAmount2' => false,
            'OrderNo' => true,
            'ProductInfo' => false,
            'Remark1' => false,
            'Remark2' => false,
        ];
    }

    public function getOperation() : string{
        return self::OPERATION_FREEZE;
    }
}
