<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BusinessUserFreezeDrawAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_user_freeze_draw}}';
    }
}
