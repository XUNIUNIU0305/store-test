<?php
namespace business\models;

use Yii;
use common\models\Model;
use business\models\parts\Account;
use business\models\parts\SmsCaptcha;

class PasswordModel extends Model{

    const SCE_RESET_PASSWORD = 'reset_password';

    public $account;
    public $mobile;
    public $mobile_captcha;
    public $passwd;
    public $passwd_confirm;

    public function scenarios(){
        return [
            self::SCE_RESET_PASSWORD => [
                'account',
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
                ['account', 'mobile', 'mobile_captcha', 'passwd', 'passwd_confirm'],
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
                ['mobile'],
                'integer',
                'min' => 10000000000,
                'max' => 19999999999,
                'tooSmall' => 9002,
                'tooBig' => 9002,
                'message' => 9002,
            ],
            [
                ['mobile_captcha'],
                'integer',
                'min' => 100000,
                'max' => 999999,
                'tooSmall' => 13176,
                'tooBig' => 13176,
                'message' => 13176,
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

    public function resetPassword(){
        try{
            $account = new Account(['account' => $this->account]);
            if($account->mobile != $this->mobile)throw new \Exception('the phone number is not same');
        }catch(\Exception $e){
            $this->addError('resetPassword', 13241);
            return false;
        }
        if(!SmsCaptcha::validateCaptcha($this->mobile, $this->mobile_captcha)){
            $this->addError('resetPassword', 13176);
            return false;
        }
        if($account->resetPassword($this->passwd, true, false)){
            return true;
        }else{
            $this->addError('resetPassword', 13242);
            return false;
        }
    }
}
