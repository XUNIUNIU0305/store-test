<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AlipayNotifyLogAR extends ActiveRecord{

    public static function tableName(){
        return '{{%alipay_notify_log}}';
    }
}
