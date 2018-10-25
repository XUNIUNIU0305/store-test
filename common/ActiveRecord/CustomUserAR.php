<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class CustomUserAR extends ActiveRecord{

    public static function tableName(){
        return '{{%custom_user}}';
    }
}
