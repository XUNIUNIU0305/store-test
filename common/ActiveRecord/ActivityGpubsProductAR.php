<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class ActivityGpubsProductAR extends ActiveRecord{

    public static function tableName(){
        return '{{%activity_gpubs_product}}';
    }
}
