<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class TempYougaZodiacAR extends ActiveRecord{

    public static function tableName(){
        return '{{%temp_youga_zodiac}}';
    }
}
