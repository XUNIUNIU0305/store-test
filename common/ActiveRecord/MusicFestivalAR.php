<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class MusicFestivalAR extends ActiveRecord{

    public static function tableName(){
        return '{{%music_festival}}';
    }
}
