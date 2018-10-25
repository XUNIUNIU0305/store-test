<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//交易明细查询
class NanjingQueryPaymentAndRefundStatement extends Base{

    public function getAttrs() : array{
        return [
            'MerchantId' => true,
            'MerUserId' => false,
            'MerchantSeqNo' => false,
            'OrderNo' => false,
            'BeginDate' => false,
            'EndDate' => false,
            'Record' => false,
            'Limit' => false,
        ];
    }

    public function getOperation() : string{
        return self::OPERATION_QUERY_PAYMENT_AND_REFUND_STATEMENT;
    }
}
