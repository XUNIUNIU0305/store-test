<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class NanjingAccountAR extends ActiveRecord{

    public static function tableName(){
        return '{{%nanjing_account}}';
    }
}
