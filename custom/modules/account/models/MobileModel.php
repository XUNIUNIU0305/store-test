<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/23
 * Time: 9:50
 */

namespace custom\modules\account\models;


use common\models\Model;
use custom\models\parts\sms\SmsCaptcha;
use custom\models\SmsModel;
use Yii;

class MobileModel extends Model
{


    //给当前用户发送短信验证码
    const SCE_SEND_CURRENT_USER = "send_for_current_user";
    //验证短信验证码是否正确
    const SCE_CHECK_VERIFY_CODE = "check_verify_code";
    //更换绑定的手机号码
    const SCE_BIND_NEW_MOBILE = "bind_new_mobile";

    //首页绑定号码
    const SCE_BIND_MOBILE = "bind_mobile";
    //获取用户手机信息
    const SCE_GET_MOBILE = "get_mobile";


    public $verify_code;
    public $old_verify_code;
    public $mobile;
    public $type;

    public function scenarios()
    {
        return [
            self::SCE_SEND_CURRENT_USER => ['type'],
            self::SCE_CHECK_VERIFY_CODE => ['mobile', 'verify_code'],
            self::SCE_BIND_NEW_MOBILE => ['mobile', 'verify_code'],
            self::SCE_GET_MOBILE => [],
            self::SCE_BIND_MOBILE => ['mobile', 'verify_code'],
        ];

    }

    public function rules()
    {
        return [
            [
                ['verify_code'],
                'required',
                'message' => 9001,
            ],
            [
                ['mobile'],
                'integer',
                'min' => 10000000000,
                'max' => 19999999999,
                'tooSmall' => 3166,
                'tooBig' => 3166,
                'message' => 3166,
            ],
            [
                ['mobile', 'verify_code'],
                'required',
                'message' => 9001,
                'on' => [self::SCE_BIND_NEW_MOBILE],
            ],
            [
                ['mobile'],
                'common\validators\custom\AccountMobileValidator',
                'message' => 3257,
                'on' => [self::SCE_BIND_NEW_MOBILE,self::SCE_BIND_MOBILE],
            ],
            [
                ['mobile'],
                'required',
                'message' => 9001,
                'on' => [self::SCE_BIND_MOBILE],
            ],
            [
                ['verify_code'],
                'common\validators\SmsValidator',
                'mobile' => $this->mobile,
                'message' => 3252,
                'on' => [self::SCE_BIND_NEW_MOBILE, self::SCE_BIND_MOBILE],
            ],
            /*
            [
                ['old_verify_code'],
                'common\validators\SmsValidator',
                'mobile' => Yii::$app->CustomUser->CurrentUser->getMobile(),
                'message' => 3252,
                'on' => [self::SCE_BIND_NEW_MOBILE],
            ],*/


        ];
    }

    //获取当前用户mobile信息
    public function getMobile()
    {
        $mobile = Yii::$app->CustomUser->CurrentUser->getMobile();
        if ($mobile) {
            $mobile = substr($mobile, 0, 3) . "****" . substr($mobile, -4);
        } else {
            $mobile = 0;
        }
        return ["mobile" => $mobile];
    }

    //首页绑定手机号码
    public function bindMobile()
    {
        if (Yii::$app->CustomUser->CurrentUser->setMobile($this->mobile, false) !== false) {
            return true;
        }

        $this->addError("bindMobile", 3254);
        return false;
    }

    //绑定用户手机
    public function bindNewMobile()
    {
        //验证有效性
        if (Yii::$app->session->getFlash('verify_status') != true) {
            $this->addError("bindMobile", 3254);
            return false;
        }

        if (Yii::$app->CustomUser->CurrentUser->setMobile($this->mobile, false) !== false) {
            return true;
        }
        $this->addError("bindMobile", 3254);
        return false;
    }

    //检测验证码
    public function checkVerifyCode()
    {

        if (!$this->mobile) {
            $this->mobile = Yii::$app->CustomUser->CurrentUser->getMobile();
        }
        if (!$this->mobile) {
            $this->addError('checkVerifyCode', 3255);
            return false;
        }

        if (SmsCaptcha::validateCaptcha($this->mobile, $this->verify_code)) {
            //记录首次验证
            Yii::$app->session->setFlash('verify_status', true);
            return true;
        }

        $this->addError('checkVerifyCode', 3252);
        return false;
    }


    //给当前登录用户发送验证码
    public function sendForCurrentUser()
    {
        if ((new SmsModel([
            'scenario' => SmsModel::SCE_SEND,
            'attributes' => ['mobile' => Yii::$app->CustomUser->CurrentUser->getMobile(),'type'=>$this->type?$this->type:3],
        ]))->sendSms()){
            return true;
        }
        $this->addError('sendForCurrentUser', 3251);
        return false;
    }

}