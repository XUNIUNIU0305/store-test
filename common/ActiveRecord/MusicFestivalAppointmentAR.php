<?php
namespace common\ActiveRecord;

use yii\db\ActiveRecord;

class MusicFestivalAppointmentAR extends ActiveRecord{

    public static function tableName(){
        return '{{%music_festival_appointment}}';
    }
}
