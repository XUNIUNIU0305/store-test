<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BusinessUserThawDrawAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_user_thaw_draw}}';
    }
}
