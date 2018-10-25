<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ActivityGpubsGroupDetailAR extends ActiveRecord{

    public static function tableName(){
        return '{{%activity_gpubs_group_detail}}';
    }
}
