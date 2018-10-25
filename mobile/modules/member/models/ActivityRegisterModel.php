<?php
namespace mobile\modules\member\models;

use Yii;
use common\models\Model;
use custom\components\handler\RegistercodeHandler;
use custom\models\RegisterModel;
use business\models\parts\Area;
use custom\models\SmsModel;

class ActivityRegisterModel extends Model{

    private $_registerConfig = [
        'wh' => [
            'business_area_id' => 6045,
            'district' => [
                'province_id' => 17,
                'city_id' => 171,
                'district_id' => 1503,
            ],
            'active_time' => [
                '2018-06-01 00:00:00',
                '2018-07-31 23:59:59',
            ],
        ],
        'gz' => [
            'business_area_id' => 6048,
            'district' => [
                'province_id' => 19,
                'city_id' => 202,
                'district_id' => 1725,
            ],
            'active_time' => [
                '2018-06-01 00:00:00',
                '2018-07-31 23:59:59',
            ],
        ],
    ];

    const SCE_RENDER = 'render';
    const SCE_SIGN_UP = 'sign_up';
    const SCE_SEND_CAPTCHA = 'send_captcha';

    public $c;
    public $mobile;
    public $passwd;
    public $confirm_passwd;
    public $verify_code;

    public function scenarios(){
        return [
            self::SCE_RENDER => [
                'c',
            ],
            self::SCE_SIGN_UP => [
                'c',
                'mobile',
                'passwd',
                'confirm_passwd',
                'verify_code',
            ],
            self::SCE_SEND_CAPTCHA => [
                'c',
                'mobile',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['c', 'mobile', 'passwd', 'confirm_passwd', 'verify_code'],
                'required',
                'message' => 9001,
            ],
            [
                ['c'],
                'mobile\modules\member\validators\CValidator',
                'validValue' => array_keys($this->_registerConfig),
                'startTime' => $this->_registerConfig[$this->c]['active_time'][0] ?? '',
                'endTime' => $this->_registerConfig[$this->c]['active_time'][1] ?? '',
                'invalidTimeMessage' => 10101,
                'message' => 10102,
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
        ];
    }

    public function render(){
        return true;
    }

    public function signUp(){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $registercode = current(RegistercodeHandler::createPartnerCode(1, $this->achieveArea($this->c)))->account;
            $registerModel = new RegisterModel([
                'scenario' => RegisterModel::SCE_SIGN_UP,
                'attributes' => [
                    'account' => $registercode,
                    'passwd' => $this->passwd,
                    'confirm_passwd' => $this->confirm_passwd,
                    'province' => $this->_registerConfig[$this->c]['district']['province_id'],
                    'city' => $this->_registerConfig[$this->c]['district']['city_id'],
                    'district' => $this->_registerConfig[$this->c]['district']['district_id'],
                    'mobile' => $this->mobile,
                    'email' => $registercode . '@unknown.com',
                    'verify_code' => $this->verify_code,
                ],
            ]);
            if($registerModel->process()){
                $transaction->commit();
                return ['url' => '/'];
            }else{
                if($registerModel->errorCode == 3170){
                    $transaction->commit();
                    return ['url' => '/member/login'];
                }else{
                    throw new \Exception;
                }
            }
        }catch(\Exception $e){
            $transaction->rollBack();
            try{
                $this->addError('signUp', $registerModel->errorCode);
            }catch(\Exception $e){
                $this->addError('signUp', 9002);
            }
            return false;
        }
    }

    public function sendCaptcha(){
        $smsModel = new SmsModel([
            'scenario' => SmsModel::SCE_SEND,
            'attributes' => [
                'mobile' => $this->mobile,
                'type' => 0,
            ],
        ]);
        if($smsModel->process()){
            return true;
        }else{
            $this->addError('sendCaptcha', $smsModel->errorCode);
            return false;
        }
    }

    protected function achieveArea($c){
        if(!in_array($c, array_keys($this->_registerConfig)))return false;
        return new Area([
            'id' => $this->_registerConfig[$c]['business_area_id'],
        ]);
    }
}
