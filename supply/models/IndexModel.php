<?php
namespace supply\models;

use Yii;
use common\models\Model;
use supply\models\parts\UserIdentity;
use supply\models\parts\CaptchaModel;

class IndexModel extends Model{

    const SCE_LOGIN = 'login';

    public $account;
    public $passwd;
    public $captcha;

    private $_userIdentity;

    public function scenarios(){
        return[
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
                'message' => 1002,
                'tooShort' => 1002,
                'tooLong' => 1002,
            ],
            [
                ['captcha'],
                'captcha',
                'captchaAction' => 'index/captcha',
                'message' => 1001,
            ],
        ];
    }

    /**
     * 验证用户
     *
     * @return bool
     */
    public function verifyUser(){
        if(!$this->validate())return false;
        $userAR = UserIdentity::findOne(['account' => $this->account]);
        if($userAR && Yii::$app->getSecurity()->validatePassword($this->passwd, $userAR->passwd)){
            $this->_userIdentity = $userAR;
            return true;
        }else{
            $this->addError('verifyUser', 1003);
            return false;
        }
    }

    /**
     * 获取已登陆用户identity
     *
     * @return null | UserIdentity
     */
    public function getUserIdentity(){
        return $this->_userIdentity;
    }

    /**
     * 验证【验证码】
     *
     * @return boolean
     */
    public static function verifyCaptcha($captcha){
        return CaptchaModel::verify($captcha);
    }
}
