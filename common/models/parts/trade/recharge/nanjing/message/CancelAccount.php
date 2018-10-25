<?php
namespace common\models\parts\trade\recharge\nanjing\message;

use Yii;
use common\ActiveRecord\NanjingAccountAR;

class CancelAccount extends BaseAbstract{

    public $accountId;
    public $cancelDatetime;
    public $cancelUnixtime;

    protected function runExtra() : bool{
        Yii::$app->RQ->AR(NanjingAccountAR::findOne($this->accountId))->update([
            'cancel_datetime' => $this->cancelDatetime,
            'cancel_unixtime' => $this->cancelUnixtime,
            'is_available' => 0,
        ]);
        return true;
    }
}
