<?php
namespace common\models\parts\trade\recharge\nanjing\message;

use Yii;
use common\ActiveRecord\NanjingAccountAR;

class CreateAccount extends BaseAbstract{

    public $callbackPlain;
    public $originalPlain;
    public $accountObj;
    public $accountId;
    public $mobilePhone;
    public $cifType;
    public $cifName;
    public $idType;
    public $idNo;
    public $acctType;
    public $acctName;
    public $acctNo;
    public $bankType;
    public $branchId;
    public $createDatetime;
    public $createUnixtime;
    protected $instance;

    protected function getInstanceConfig() : array{
        return [
            'account' => [
                'class' => $this->accountObj,
                'id' => $this->accountId,
            ],
        ];
    }

    protected function runExtra() : bool{
        $account = $this->instance->account;
        Yii::$app->RQ->AR(new NanjingAccountAR)->insert([
            'nanjing_userid' => $account->merUserId,
            'user_id' => $account->id,
            'user_type' => $account->userType,
            'user_account' => $account->userAccount,
            'mobile_phone' => $this->mobilePhone,
            'cif_type' => $this->cifType,
            'cif_name' => $this->cifName,
            'id_type' => $this->idType,
            'id_no' => $this->idNo,
            'acct_type' => $this->acctType,
            'acct_name' => $this->acctName,
            'acct_no' => $this->acctNo,
            'bank_type' => $this->bankType,
            'branch_id' => $this->branchId,
            'vir_acct_no' => $this->callbackPlain['VirAcctNo'],
            'vir_acct_name' => $this->callbackPlain['VirAcctName'],
            'is_active' => $this->callbackPlain['IsActive'],
            'create_datetime' => $this->createDatetime,
            'create_unixtime' => $this->createUnixtime,
        ]);
        return true;
    }
}
