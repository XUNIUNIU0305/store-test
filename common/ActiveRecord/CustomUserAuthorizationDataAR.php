<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class CustomUserAuthorizationDataAR extends ActiveRecord{

    public static function tableName(){
        return '{{%custom_user_authorization_data}}';
    }
}
