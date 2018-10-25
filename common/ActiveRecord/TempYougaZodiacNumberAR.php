<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class TempYougaZodiacNumberAR extends ActiveRecord{

    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'selected_datetime',
                'updatedAtAttribute' => false,
                'value' => '0000-01-01 00:00:00',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => false,
                'updatedAtAttribute' => 'selected_datetime',
                'value' => Yii::$app->time->fullDate,
            ],
        ];
    }

    public static function tableName(){
        return '{{%temp_youga_zodiac_number}}';
    }
}
