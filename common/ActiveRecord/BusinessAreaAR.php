<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class BusinessAreaAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_area}}';
    }
}
