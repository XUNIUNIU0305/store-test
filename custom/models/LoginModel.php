<?php
namespace custom\models;

use Yii;
use common\models\Model;
use custom\models\parts\Captcha;
use custom\models\parts\UserIdentity;

class LoginModel extends Model{

    const SCE_LOGIN = 'login';
    const SCE_VERIFY_IDENTITY = 'verify_identity';

    public $account;
    public $passwd;
    public $captcha;

    public function scenarios(){
        return [
            self::SCE_LOGIN => [
                'account',
                'passwd',
                'captcha',
            ],
            self::SCE_VERIFY_IDENTITY => [
                'account',
                'passwd',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['account', 'passwd', 'captcha'],
                'required',
                'message' => 9001,
            ],
            [
                ['account', 'passwd'],
                'string',
                'length' => [1, 40],
                'tooShort' => 9002,
                'tooLong' => 9002,
                'message' => 9002,
            ],
            [
                ['captcha'],
                'captcha',
                'captchaAction' => 'index/captcha',
                'message' => 3011,
            ],
        ];
    }

    public function verifyIdentity(){
        $userIdentity = UserIdentity::findOne(['account' => $this->account]);
        if($userIdentity && Yii::$app->getSecurity()->validatePassword($this->passwd, $userIdentity->passwd)){
            return ['result' => true];
        }else{
            return ['result' => false];
        }
    }

    public function login(){
        if(strlen($this->account)==9) {
            //账号登录
            $userIdentity = UserIdentity::findOne([
                'account' => $this->account,
                'status' => 0,
            ]);
        }elseif(strlen($this->account)==11){
            //手机登录
            $userIdentity = UserIdentity::findOne([
                'mobile' => $this->account,
                'status' => 0,
            ]);
        }else{
            $this->addError('login',3012);
            return false;
        }
        if($userIdentity && Yii::$app->getSecurity()->validatePassword($this->passwd, $userIdentity->passwd)){
            if(Yii::$app->user->login($userIdentity)){
                return Yii::$app->user->returnUrl ? ['url' => Yii::$app->user->returnUrl] : true;
            }else{
                $this->addError('login', 3013);
                return false;
            }
        }else{
            $this->addError('login', 3012);
            return false;
        }
    }

    public static function verifyCaptcha($captcha){
        return Captcha::verify($captcha);
    }
}
