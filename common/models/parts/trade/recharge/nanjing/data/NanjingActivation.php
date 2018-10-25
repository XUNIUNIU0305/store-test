<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//二级账户激活
class NanjingActivation extends Base{

    public function getAttrs() : array{
        return [
            'MerchantId' => true,
            'MerUserId' => true,
            'VerSeqNo' => false,
            'CheckAmount' => false,
            'Currency' => false,
        ];
    }

    public function getOperation() : string{
        return self::OPERATION_ACTIVATION;
    }
}
