<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class AbchinaNotifyLogAR extends ActiveRecord{

    public static function tableName(){
        return '{{%abchina_notify_log}}';
    }
}
