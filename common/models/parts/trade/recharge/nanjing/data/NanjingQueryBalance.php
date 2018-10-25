<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//余额查询
class NanjingQueryBalance extends Base{

    public function getAttrs() : array{
        return [
            'MerchantId' => true,
            'MerUserId' => false,
            'VirAcctNo' => false,
        ];
    }

    public function getOperation() : string{
        return self::OPERATION_QUERY_BALANCE;
    }
}
