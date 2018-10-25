<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/22
 * Time: 17:53
 */

namespace custom\models;


use common\ActiveRecord\CustomUserAR;
use common\models\Model;


class ForgetModel extends Model
{


    const SCE_MODIFY_PASSWORD = "reset_password";


    public $new_password;
    public $confirm_password;
    public $mobile;
    public $verify_code;

    //设置场景
    public function scenarios()
    {
        return [
            self::SCE_MODIFY_PASSWORD => ['new_password', 'confirm_password', 'mobile', 'verify_code']
        ];
    }

    //设置规则
    public function rules()
    {
        return [
            [
                ['new_password', 'confirm_password', 'mobile', 'verify_code'],
                'required',
                'message' => 9001,
            ],
            [
                'new_password',
                'string',
                'length' => [8, 20],
                'tooShort' => 3162,
                'tooLong' => 3162,
                'message' => 3162,
            ],
            [
                ['confirm_passwd'],
                'required',
                'requiredValue' => $this->new_password,
                'message' => 3163,
            ],
            /*验证短信验证码*/
            [
                ['verify_code'],
                'common\validators\SmsValidator',
                'mobile' => $this->mobile,
                'message' => 3252,
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
                ['mobile'],
                'exist',
                'targetClass' => CustomUserAR::className(),
                'targetAttribute' => ['mobile' => 'mobile'],
                'message' => 3259
            ],
            
        ];
    }


    //重置用户密码
    public function resetPassword()
    {
        $account = new \common\models\parts\custom\CustomUser(['mobile' => $this->mobile]);
        if ($account->setPassword($this->new_password,false)) {
            return true;
        }
        $this->addError('resetPassword', 3253);
        return false;
    }

}