<?php
namespace admin\models;

use admin\models\parts\role\AdminAccount;
use Yii;
use common\models\Model;
use admin\models\parts\UserIdentity;

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
                'message' => 5001,
            ],
        ];
    }

    public function login(){
        $userIdentity = UserIdentity::findOne(['account' => $this->account,'status'=>AdminAccount::ACCOUNT_STATUS_START]);
        if($userIdentity && Yii::$app->security->validatePassword($this->passwd, $userIdentity->passwd)){
            if(Yii::$app->user->login($userIdentity)){
                return ['url' => '/main'];
            }else{
                $this->addError('login', 5002);
                return false;
            }
        }else{
            $this->addError('login', 5003);
            return false;
        }
    }

    public static function logout(){
        if(Yii::$app->user->logout()){
            return true;
        }else{
            $this->addError('logout', 5021);
            return false;
        }
    }
}
