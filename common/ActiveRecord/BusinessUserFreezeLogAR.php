<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BusinessUserFreezeLogAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_user_freeze_log}}';
    }
}
