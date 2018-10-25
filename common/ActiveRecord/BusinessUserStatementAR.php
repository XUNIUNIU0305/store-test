<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class BusinessUserStatementAR extends ActiveRecord{

    public static function tableName(){
        return '{{%business_user_statement}}';
    }
}
