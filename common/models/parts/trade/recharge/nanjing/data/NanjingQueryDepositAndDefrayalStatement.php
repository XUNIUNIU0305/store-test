<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//出入金明细查询
class NanjingQueryDepositAndDefrayalStatement extends Base{

    public function getAttrs() : array{
        return [
            'MerchantId' => true,
            'MerUserId' => false,
            'MerchantSeqNo' => false,
            'BeginDate' => false,
            'EndDate' => false,
            'Record' => false,
            'Limit' => false,
        ];
    }

    public function getOperation() : string{
        return self::OPERATION_QUERY_DEPOSIT_AND_DEFRAYAL_STATEMENT;
    }
}
