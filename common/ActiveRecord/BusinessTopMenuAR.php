<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class BusinessTopMenuAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_top_menu}}';
    }
}
