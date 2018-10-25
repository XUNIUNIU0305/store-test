<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class BusinessRoleAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_role}}';
    }
}
