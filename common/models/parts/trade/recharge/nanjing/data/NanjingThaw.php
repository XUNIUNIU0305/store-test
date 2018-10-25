<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//资金解冻（担保交易功能）
class NanjingThaw extends Base{

    public function getAttrs() : array{
        return [
            'MerchantId' => true,
            'MerchantSeqNo' => true,
            'MerchantDateTime' => true,
            'OrgMerchantSeqNo' => true,
        ];
    }

    public function getOperation() : string{
        return self::OPERATION_THAW;
    }
}
