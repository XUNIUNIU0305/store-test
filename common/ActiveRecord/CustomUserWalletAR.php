<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class CustomUserWalletAR extends ActiveRecord{

    public static function tableName(){
        return '{{%custom_user_wallet}}';
    }
}