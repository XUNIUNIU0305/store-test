<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class OSSUploadFileAR extends ActiveRecord{

    //OSS使用者 类型
    const SUPPLY_USER = 0;
    const CUSTOM_USER = 1;
    const ADMIN_USER = 2;

    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'upload_datetime',
                'updatedAtAttribute' => false,
                'value' => Yii::$app->time->fullDate,
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'upload_unixtime',
                'updatedAtAttribute' => false,
                'value' => Yii::$app->time->unixTime,
            ],
        ];
    }

    public static function tableName(){
        return '{{%oss_upload_file}}';
    }
}
