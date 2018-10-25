<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//冻结、解冻明细查询
class NanjingQueryFreezeAndThaw extends Base{

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
        return self::OPERATION_QUERY_FREEZE_AND_THAW;
    }
}
