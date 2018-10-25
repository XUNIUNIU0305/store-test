<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class DistrictProvinceAR extends ActiveRecord{

    const SHOW = 1;
    const HIDE = 0;

    public static function tableName(){
        return '{{%district_province}}';
    }
}
