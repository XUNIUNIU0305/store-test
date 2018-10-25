<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//确认/撤销支付（基于担保支付）
class NanjingConfirmAndCancelPayment extends Base{

    public function getAttrs() : array{
        return [
            'MerchantId' => true,
            'MerUserId' => false,
            'MerchantSeqNo' => true,
            'MerchantDateTime' => true,
            'OrgMerchantSeqNo' => true,
            'OperType' => true,
            'Remark1' => false,
            'Remark2' => false,
        ];
    }

    public function getOperation() : string{
        return self::OPERATION_CONFIRM_AND_CANCEL_PAYMENT;
    }
}
