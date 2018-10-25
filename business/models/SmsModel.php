<?php
namespace business\models;

use Yii;
use common\models\Model;
use business\models\parts\SmsCaptcha;
use common\models\parts\sms\SmsSender;
use business\models\parts\Account;

class SmsModel extends Model{

    const SCE_REGISTER = 'register';
    const SCE_RESET_PASSWORD = 'reset_password';
    const SCE_DRAW = 'draw';

    public $account;
    public $mobile;

    public function scenarios(){
        return [
            self::SCE_REGISTER => [
                'account',
                'mobile',
            ],
            self::SCE_RESET_PASSWORD => [
                'account',
                'mobile',
            ],
            self::SCE_DRAW => [],
        ];
    }

    public function rules(){
        return [
            [
                ['account', 'mobile', 'rmb'],
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
                ['mobile'],
                'business\validators\MobileValidator',
                'exist' => 13178,
                'message' => 13179,
                'on' => self::SCE_REGISTER,
            ],
        ];
    }

    public function draw(){
        $sms = new SmsCaptcha([
            'mobile' => Yii::$app->BusinessUser->account->mobile,
            'signName' => '九大爷平台',
            'templateCode' => 'SMS_94640099',
            'param' => [
                'captcha' => rand(100000, 999999),
            ],
        ]);
        if((new SmsSender)->send($sms, false)){
            return true;
        }else{
            $this->addError('draw', 13162);
            return false;
        }
    }

    public function resetPassword(){
        try{
            $account = new Account(['account' => $this->account]);
            if($account->mobile != $this->mobile)throw new \Exception('the phone number is not same');
        }catch(\Exception $e){
            $this->addError('resetPassword', 13241);
            return false;
        }
        $sms = new SmsCaptcha([
            'mobile' => $this->mobile,
            'signName' => '九大爷平台',
            'templateCode' => 'SMS_58200292',
            'param' => [
                'captcha' => rand(100000, 999999),
            ],
        ]);
        if((new SmsSender)->send($sms, false)){
            return true;
        }else{
            $this->addError('resetPassword', 13162);
            return false;
        }
    }

    public function register(){
        try{
            $account = new Account(['account' => $this->account]);
            if($account->status != Account::STATUS_UNREGISTERED)throw new \Exception('this account has been registered');
        }catch(\Exception $e){
            $this->addError('validateRegistercode', 13161);
            return false;
        }
        $sms = new SmsCaptcha([
            'mobile' => $this->mobile,
            'signName' => '九大爷平台',
            'templateCode' => 'SMS_58285247',
            'param' => [
                'captcha' => rand(100000, 999999),
            ],
        ]);
        if((new SmsSender)->send($sms, false)){
            return true;
        }else{
            $this->addError('register', 13162);
            return false;
        }
    }
}
