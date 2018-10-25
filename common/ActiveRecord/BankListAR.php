<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BankListAR extends ActiveRecord{

    public static function tableName(){
        return '{{%bank_list}}';
    }
}
