<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class WxpayNotifyLogAR extends ActiveRecord{

    public static function tableName(){
        return '{{%wxpay_notify_log}}';
    }
}
