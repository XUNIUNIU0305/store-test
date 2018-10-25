<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class NanjingExecuteLogAR extends ActiveRecord{

    public static function tableName(){
        return '{{%nanjing_execute_log}}';
    }
}
