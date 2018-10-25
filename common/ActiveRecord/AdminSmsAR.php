<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AdminSmsAR extends ActiveRecord{

    public static function tableName(){
        return '{{%admin_sms}}';
    }
}
