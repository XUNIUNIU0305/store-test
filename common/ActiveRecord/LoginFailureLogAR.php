<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class LoginFailureLogAR extends ActiveRecord{

    public static function tableName(){
        return '{{%login_failure_log}}';
    }
}
