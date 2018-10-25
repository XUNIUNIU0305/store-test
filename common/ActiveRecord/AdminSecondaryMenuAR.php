<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class AdminSecondaryMenuAR extends ActiveRecord{

    public static function tableName(){
        return '{{%admin_secondary_menu}}';
    }
}
