<?php
namespace admin\models\parts\sms;

use Yii;
use common\models\parts\sms\SmsAbstract;

class Sms extends SmsAbstract{

    protected static function getSiteBasedSignNames(){
        return [];
    }

    protected static function getSiteBasedTemplates(){
        return [];
    }

    public static function getSendIntervalSecond(){
        return 0;
    }

    public function doAfterSend($sendResult, $return = 'throw'){
        return;
    }
}
