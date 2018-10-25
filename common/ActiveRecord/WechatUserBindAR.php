<?php
namespace common\ActiveRecord;

use Yii;
use yii\db\ActiveRecord;

class WechatUserBindAR extends ActiveRecord{

    public static function tableName(){
        return '{{%wechat_user_bind}}';
    }
}
