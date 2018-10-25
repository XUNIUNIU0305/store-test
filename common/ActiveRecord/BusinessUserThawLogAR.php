<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BusinessUserThawLogAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_user_thaw_log}}';
    }
}
