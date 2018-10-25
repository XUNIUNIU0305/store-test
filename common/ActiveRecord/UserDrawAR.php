<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class UserDrawAR extends ActiveRecord{

    public static function tableName(){
        return '{{%user_draw}}';
    }
}
