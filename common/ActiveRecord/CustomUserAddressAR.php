<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class CustomUserAddressAR extends ActiveRecord{

    const DEFAULT_ADDRESS = 1;
    const NORMAL_ADDRESS = 0;

    public static function tableName(){
        return '{{%custom_user_address}}';
    }
}
