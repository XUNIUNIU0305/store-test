<?php
namespace business\models;

use Yii;
use common\models\Model;
use business\models\parts\Account;
use business\models\parts\SmsCaptcha;

class RegisterModel extends Model{

    const SCE_VALIDATE_REGISTERCODE = 'validate_registercode';
    const SCE_REGISTER_ACCOUNT = 'register_account';

    public $account;
    public $name;
    public $mobile;
    public $mobile_captcha;
    public $passwd;
    public $passwd_confirm;

    public function scenarios(){
        return [
            self::SCE_VALIDATE_REGISTERCODE => [
                'account',
            ],
            self::SCE_REGISTER_ACCOUNT => [
                'account',
                'name',
                'mobile',
                'mobile_captcha',
                'passwd',
                'passwd_confirm',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['account', 'name', 'mobile', 'mobile_captcha', 'passwd', 'passwd_confirm'],
                'required',
                'message' => 9001,
            ],
            [
                ['account'],
                'integer',
                'min' => 10000000,
                'max' => 99999999,
                'tooSmall' => 9002,
                'tooBig' => 9002,
                'message' => 9002,
            ],
            [
                ['name'],
                'string',
                'length' => [2, 30],
                'tooShort' => 13171,
                'tooLong' => 13171,
                'message' => 13171,
            ],
            [
                ['mobile'],
                'business\validators\MobileValidator',
                'exist' => 13178,
                'message' => 9002,
            ],
            [
                ['mobile_captcha'],
                'integer',
                'min' => 100000,
                'max' => 999999,
                'tooSmall' => 13172,
                'tooBig' => 13172,
                'message' => 13172,
            ],
            [
                ['passwd'],
                'string',
                'length' => [8, 100],
                'tooShort' => 13173,
                'tooLong' => 13174,
                'message' => 9002,
            ],
            [
                ['passwd_confirm'],
                'required',
                'requiredValue' => $this->passwd,
                'message' => 13175,
            ],
        ];
    }

    public function registerAccount(){
        if(!SmsCaptcha::validateCaptcha($this->mobile, $this->mobile_captcha)){
            $this->addError('registerAccount', 13176);
            return false;
        }
        try{
            $account = new Account(['account' => $this->account]);
            if($account->status != Account::STATUS_UNREGISTERED)throw new \Exception('this account has been registered');
        }catch(\Exception $e){
            $this->addError('validateRegistercode', 13151);
            return false;
        }
        if($account->name != $this->name){
            $this->addError('registerAccount', 13180);
            return false;
        }
        if($account->register($this->passwd, (int)$this->mobile, false)){
            return true;
        }else{
            $this->addError('registerAccount', 13177);
            return false;
        }
    }

    public function validateRegistercode(){
        try{
            $account = new Account(['account' => $this->account]);
            if($account->status != Account::STATUS_UNREGISTERED)throw new \Exception('this account has been registered');
        }catch(\Exception $e){
            $this->addError('validateRegistercode', 13151);
            return false;
        }
        return [
            'last_name' => mb_substr($account->name, 0, 1, 'utf-8'),
        ];
    }
}
