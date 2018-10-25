<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class CustomAccountTopMenuAR extends ActiveRecord{

    public static function tableName(){
        return '{{%custom_account_top_menu}}';
    }
}
