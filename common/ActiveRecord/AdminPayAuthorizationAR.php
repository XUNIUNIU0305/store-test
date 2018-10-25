<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AdminPayAuthorizationAR extends ActiveRecord{

    public static function tableName(){
        return '{{%admin_pay_authorization}}';
    }
}
