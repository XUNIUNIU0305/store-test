<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ActivityGpubsGroupDetailPickLogAR extends ActiveRecord{

    public static function tableName(){
        return '{{%activity_gpubs_group_detail_pick_log}}';
        
    }
}
