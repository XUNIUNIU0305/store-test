<?php
namespace admin\models\parts\sms;

use Yii;
use common\ActiveRecord\AdminSmsAR;
use common\models\parts\sms\SmsCaptchaAbstract;

class SmsCaptcha extends SmsCaptchaAbstract{

    protected static function getSiteBasedSignNames(){
        return [];
    }

    protected static function getSiteBasedTemplates(){
        return [
            //邀请门店加入
            'SMS_78610075' => [
                'message' => '您正在进行邀请付款操作，请于五分钟内在页面输入验证码${captcha}。付款成功后，您的注册码将会发送至此手机。',
                'params' => ['captcha'],
            ],
            //门店已加入发送注册码
            'SMS_78540086' => [
                'message' => '感谢您的加入，我们已收到你的付款。请登入http://t.cn/RKuzj6a，使用注册码[${captcha}]完成注册，为保障权益，切勿将信息泄露给其他人。',
                'params' => ['captcha'],
            ],
        ];
    }

    public static function getSendIntervalSecond(){
        return 0;
    }

    public function doAfterSend($sendResult, $return = 'throw'){
        return;
    }

    protected static function getActiveRecord(){
        return new AdminSmsAR();
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
        return 'send_date_time';
    }

    protected static function getSendUnixtimeField(){
        return 'send_unix_time';
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
