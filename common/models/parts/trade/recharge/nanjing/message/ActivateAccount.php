<?php
namespace common\models\parts\trade\recharge\nanjing\message;

use Yii;
use common\ActiveRecord\NanjingAccountAR;

class ActivateAccount extends BaseAbstract{

    public $callbackPlain;
    public $accountId;
    public $isActive;

    protected function runExtra() : bool{
        Yii::$app->RQ->AR(NanjingAccountAR::findOne($this->accountId))->update([
            'vir_acct_no' => $this->callbackPlain['VirAcctNo'],
            'vir_acct_name' => $this->callbackPlain['VirAcctName'],
            'is_active' => $this->isActive,
            'ver_seq_no' => '',
            'ver_seq_no_unixtime' => 0,
            'create_datetime' => date('Y-m-d H:i:s'),
            'create_unixtime' => time(),
        ]);
        return true;
    }
}
