<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//资金退货
class NanjingRefund extends Base{

    public function getAttrs() : array{
        return [
            'MerchantId' => true,
            'MerUserId' => false,
            'MerchantSeqNo' => true,
            'MerchantDateTime' => true,
            'OrgMerchantSeqNo' => true,
            'IsBackFee' => false,
            'Remark1' => false,
            'Remark2' => false,
        ];
    }

    public function getOperation() : string{
        return self::OPERATION_REFUND;
    }
}
