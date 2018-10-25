<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class ProductAR extends ActiveRecord{

    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'add_datetime',
                'updatedAtAttribute' => false,
                'value' => Yii::$app->time->fullDate,
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'add_unixtime',
                'updatedAtAttribute' => false,
                'value' => Yii::$app->time->unixTime,
            ],
        ];
    }

    public static function tableName(){
        return '{{%product}}';
    }
}
