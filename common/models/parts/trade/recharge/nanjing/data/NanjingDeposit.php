<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//入金
class NanjingDeposit extends Base{

    public function getAttrs() : array{
        return [
            'MerchantId' => true,
            'MerUserId' => true,
            'MerUserAcctNo' => true,
            'VerCode' => true,
            'VerSeqNo' => true,
            'MerchantSeqNo' => true,
            'MerchantDateTime' => true,
            'TransAmount' => true,
            'Currency' => false,
            'Remark1' => false,
            'Remark2' => false,
        ];
    }

    public function getOperation() : string{
        return self::OPERATION_DEPOSIT;
    }
}
