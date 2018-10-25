<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ActivityGpubsAddressAR extends ActiveRecord{

    const DEFAULT_ADDRESS = 1;
    const NORMAL_ADDRESS = 0;

    public static function tableName(){
        return '{{%activity_gpubs_address}}';
    }
}
