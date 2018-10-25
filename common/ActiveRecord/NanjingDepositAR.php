<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class NanjingDepositAR extends ActiveRecord{

    public static function tableName(){
        return '{{%nanjing_deposit}}';
    }
}
