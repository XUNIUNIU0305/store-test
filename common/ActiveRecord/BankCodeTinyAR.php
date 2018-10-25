<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BankCodeTinyAR extends ActiveRecord{

    public static function tableName(){
        return '{{%bank_code_tiny}}';
    }
}
