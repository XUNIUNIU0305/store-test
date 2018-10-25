<?php
namespace common\models\parts\trade\recharge\nanjing\message;

use Yii;

class ActivateApply extends BaseAbstract{

    public $callbackPlain;
    public $accountObj;
    public $accountId;
    public $verSeqNoUnixtime;
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
        $this->instance->account->verSeqNo = $this->callbackPlain['VerSeqNo'];
        $this->instance->account->verSeqNoUnixtime = $this->verSeqNoUnixtime;
        return true;
    }
}
