<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BusinessUserReceiveLogAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_user_receive_log}}';
    }
}
