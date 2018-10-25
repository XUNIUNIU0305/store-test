<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class WechatUserAR extends ActiveRecord{

    public static function tableName(){
        return '{{%wechat_user}}';
    }
}
