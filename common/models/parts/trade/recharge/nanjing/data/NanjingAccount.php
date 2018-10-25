<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;

//二级账户开立（开户、修改、注销）
class NanjingAccount extends Base{

    public function getAttrs() : array{
        return [
            'MerchantId' => true,
            'MerUserId' => true,
            'OperType' => true,
            'IsRate' => true,
            'MobilePhone' => true,
            'VerCode' => false,
            'VerSeqNo' => false,
            'CifType' => true,
            'CifName' => true,
            'IdType' => true,
            'IdNo' => true,
            'AcctType' => true,
            'AcctName' => true,
            'AcctNo' => true,
            'BranchId' => true,
        ];
    }

    public function getOperation() : string{
        return self::OPERATION_ACCOUNT;
    }
}
