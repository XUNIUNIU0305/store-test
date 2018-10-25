<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class NanjingDrawAR extends ActiveRecord{

    public static function tableName(){
        return '{{%nanjing_draw}}';
    }
}
