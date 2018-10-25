<?php
namespace business\models\parts;

use common\ActiveRecord\BusinessSmsAR;
use common\models\parts\sms\SmsCaptchaAbstract;

class SmsCaptcha extends SmsCaptchaAbstract{

    protected static function getSiteBasedSignNames(){
        return [];
    }

    protected static function getSiteBasedTemplates(){
        return [
            //注册账号
            'SMS_58285247' => [
                'message' => '您正在进行注册账户操作，请于五分钟内在页面输入验证码${captcha}。注册成功后，即可直接使用您的注册码进行登录。',
                'params' => ['captcha'],
            ],
            //找回密码
            'SMS_58200292' => [
                'message' => '您正在进行密码找回操作，请在页面输入验证码${captcha}，五分钟内有效，请勿告知他人。',
                'params' => ['captcha'],
            ],
        ];
    }

    public static function getSendIntervalSecond(){
        return 0;
    }

    public function doAfterSend($sendResult, $return = 'throw'){
    
    }

    protected static function getActiveRecord(){
        return new BusinessSmsAR();
    }

    protected static function getMobileField(){
        return 'mobile';
    }

    protected static function getCaptchaField(){
        return 'captcha';
    }

    protected static function getCaptchaParamName(){
        return 'captcha';
    }

    protected static function getSendDatetimeField(){
        return 'send_time';
    }

    protected static function getSendUnixtimeField(){
        return 'send_unixtime';
    }

    protected static function getStatusField(){
        return 'status';
    }

    protected static function getPrimaryField(){
        return 'id';
    }

    protected static function getExtraSaveData(){
        return [];
    }

    public static function getExpireSecond(){
        return 300;
    }
}
