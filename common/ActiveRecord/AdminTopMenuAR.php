<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class AdminTopMenuAR extends ActiveRecord{

    public static function tableName(){
        return '{{%admin_top_menu}}';
    }
}
