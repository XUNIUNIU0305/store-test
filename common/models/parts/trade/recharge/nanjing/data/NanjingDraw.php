<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//出金
class NanjingDraw extends Base{

    public function getAttrs() : array{
        return [
            'MerchantId' => true,
            'MerUserId' => true,
            'MerUserAcctNo' => true,
            'VerCode' => false,
            'WithdrawType' => false,
            'VerSeqNo' => false,
            'MerchantSeqNo' => true,
            'MerchantDateTime' => true,
            'TransAmount' => true,
            'Currency' => false,
            'OperType' => false,
            'RcvAcctNo' => false,
            'RcvAcctName' => false,
            'RcvBankId' => false,
            'Remark1' => false,
            'Remark2' => false,
        ];
    }

    public function getOperation() : string{
        return self::OPERATION_DRAW;
    }
}
