<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class BusinessUserAR extends ActiveRecord{

    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => false,
                'value' => Yii::$app->time->fullDate,
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_unixtime',
                'updatedAtAttribute' => false,
                'value' => Yii::$app->time->unixTime,
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'register_time',
                'updatedAtAttribute' => false,
                'value' => '0000-01-01 00:00:00',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'remove_time',
                'updatedAtAttribute' => false,
                'value' => '0000-01-01 00:00:00',
            ],
        ];
    }

    public static function tableName(){
        return '{{%business_user}}';
    }
}
