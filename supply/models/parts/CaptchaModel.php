<?php
namespace supply\models\parts;

use Yii;
use common\models\Model;

class CaptchaModel extends Model{

    /**
     * 验证【验证码】
     *
     * @param $captcha string 验证码
     *
     * @return bool
     */
    public static function verify(string $captcha){
        return Yii::$app->session->get('__captcha/index/captcha') === $captcha;
    }
}
