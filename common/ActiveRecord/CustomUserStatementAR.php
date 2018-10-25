<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class CustomUserStatementAR extends ActiveRecord{
    //充值
    const  ALTER_TYPE_RECEIVE = 1;

    //消费
    const  ALTER_TYPE_PAY = 2;

    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'alteration_datetime',
                'updatedAtAttribute' => false,
                'value' => Yii::$app->time->fullDate,
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'alteration_unixtime',
                'updatedAtAttribute' => false,
                'value' => Yii::$app->time->unixTime,
            ],
        ];
    }

    public static function tableName(){
        return '{{%custom_user_statement}}';
    }
}
