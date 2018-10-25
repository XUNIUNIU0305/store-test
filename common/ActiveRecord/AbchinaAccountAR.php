<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AbchinaAccountAR extends ActiveRecord{

    public static function tableName(){
        return '{{%abchina_account}}';
    }
}
