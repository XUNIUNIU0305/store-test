<?php
namespace console\models\sms;

use Yii;
use common\models\parts\sms\SmsAbstract;

class Sms extends SmsAbstract{

    protected static function getSiteBasedSignNames(){
        return [];
    }

    protected static function getSiteBasedTemplates(){
        return [
            'SMS_144456170' => [
                'message' => '截至本日${time}，银行账户余额为：${bankBalance}元，用户总余额为：${accountBalance}元，请及时充值。',
                'params' => ['time', 'bankBalance', 'accountBalance'],
            ],
        ];
    }

    public static function getSendIntervalSecond(){
        return 0;
    }

    public function doAfterSend($sendResult, $return = 'throw'){
        return true;
    }
}
