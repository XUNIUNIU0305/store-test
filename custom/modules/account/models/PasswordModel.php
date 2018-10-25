<?php
namespace custom\modules\account\models;

use Yii;
use common\models\Model;

class PasswordModel extends Model{

    const SCE_MODIFY_PASSWORD = 'modify_password';

    public $origin_passwd;
    public $new_passwd;
    public $confirm_new_passwd;
    public $captcha;

    public function scenarios(){
        return [
            self::SCE_MODIFY_PASSWORD => [
                'origin_passwd',
                'new_passwd',
                'confirm_new_passwd',
                'captcha',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['origin_passwd', 'new_passwd', 'confirm_new_passwd'],
                'required',
                'message' => 9001,
            ],
            [
                ['origin_passwd', 'new_passwd', 'confirm_new_passwd'],
                'string',
                'length' => [8, 100],
                'tooShort' => 3181,
                'tooLong' => 3181,
                'message' => 3181,
            ],
            [
                ['confirm_new_passwd'],
                'required',
                'requiredValue' => $this->new_passwd,
                'message' => 3182,
            ],
            [
                ['captcha'],
                'captcha',
                'captchaAction' => 'index/captcha',
                'message' => 3185,
            ],
        ];
    }

    public function modifyPassword(){
        if(!Yii::$app->security->validatePassword($this->origin_passwd, Yii::$app->user->identity->passwd)){
            $this->addError('modifyPassword', 3183);
            return false;
        }
        if(Yii::$app->CustomUser->modifyPasswd($this->origin_passwd, $this->new_passwd, false)){
            return true;
        }else{
            $this->addError('modifyPassword', 3184);
            return false;
        }
    }
}
