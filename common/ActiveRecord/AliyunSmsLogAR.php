<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class AliyunSmsLogAR extends ActiveRecord{

    public static function tableName(){
        return '{{%aliyun_sms_log}}';
    }
}
