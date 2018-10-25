<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//签约客户信息查询
class NanjingQueryCustomerInfo extends Base{

    public function getAttrs() : array{
        return [
            'MerchantId' => true,
            'MerUserId' => false,
            'Record' => false,
            'Limit' => false,
        ];
    }

    public function getOperation() : string{
        return self::OPERATION_QUERY_CUSTOMER_INFO;
    }
}
