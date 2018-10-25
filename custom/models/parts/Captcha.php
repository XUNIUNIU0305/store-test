<?php
namespace custom\models\parts;

use Yii;
use yii\base\Object;

class Captcha extends Object{

    public static function verify($captcha){
        return Yii::$app->session->get('__captcha/index/captcha') == $captcha;
    }
}
