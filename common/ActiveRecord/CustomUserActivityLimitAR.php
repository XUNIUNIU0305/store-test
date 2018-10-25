<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class CustomUserActivityLimitAR extends ActiveRecord{

    public static function tableName(){
        return '{{%custom_user_activity_limit}}';
    }
}
