<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AbchinaOrderAR extends ActiveRecord{

    public static function tableName(){
        return '{{%abchina_order}}';
    }
}
