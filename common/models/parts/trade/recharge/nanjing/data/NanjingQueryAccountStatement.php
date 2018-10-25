<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//二级账户明细查询
class NanjingQueryAccountStatement extends Base{

    public function getAttrs() : array{
        return [
            'MerchantId' => true,
            'MerUserId' => false,
            'VirAcctNo' => false,
            'BeginDate' => true,
            'EndDate' => true,
            'Record' => false,
            'Limit' => false,
        ];
    }

    public function getOperation() : string{
        return self::OPERATION_QUERY_ACCOUNT_STATEMENT;
    }
}
