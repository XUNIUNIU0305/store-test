<?php
namespace custom\models\parts\wechat;

use Yii;
use common\models\parts\wechat\WechatAbstract;
use common\ActiveRecord\CustomUserAR;
use custom\models\parts\UserIdentity;

class Wechat extends WechatAbstract{

    protected function getUserActiveRecord(){
        return new CustomUserAR;
    }

    protected function getUserIdentity(){
        return new UserIdentity;
    }
}
