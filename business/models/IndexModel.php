<?php
namespace business\models;

use Yii;
use common\models\Model;
use business\models\parts\UserIdentity;
use business\models\parts\Account;

class IndexModel extends Model{

    const SCE_LOGIN = 'login';

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
                ['captcha'],
                'captcha',
                'captchaAction' => 'index/captcha',
                'message' => 13001,
            ],
        ];
    }

    public function login(){
        switch(strlen($this->account)){
            case 8:
                $loginMethod = 'account';
                break;

            case 11:
                $loginMethod = 'mobile';
                break;

            default:
                return $this->loginFailed();
        }
        if($identity = UserIdentity::findOne([$loginMethod => $this->account, 'status' => Account::STATUS_NORMAL])){
            if(Yii::$app->security->validatePassword($this->passwd, $identity->passwd) && Yii::$app->user->login($identity)){
                return true;
            }else{
                return $this->loginFailed();
            }
        }else{
            return $this->loginFailed();
        }
    }

    private function loginFailed(){
        $this->addError('login', 13002);
        return false;
    }
}
