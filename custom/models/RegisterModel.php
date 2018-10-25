<?php
namespace custom\models;

use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\CustomUserRegistercodeAR;
use Yii;
use common\models\Model;
use custom\models\parts\RegisterCode;
use common\models\parts\district\District;
use common\models\parts\business_area\QuaternaryArea;
use custom\components\handler\AccountHandler;

class RegisterModel extends Model
{

    const SCE_SIGN_UP = 'sign_up';
    const SCE_CHECK_ACCOUNT = 'check_account_status';

    public $account;
    public $passwd;
    public $confirm_passwd;
    public $province;
    public $city;
    public $district;
    public $mobile;
    public $email;
    /*
     * Mod:JiangYi
     * Date:2017/3/22
     * Desc:短信验证码
     */
    public $verify_code;

    public function scenarios()
    {
        return [
            self::SCE_SIGN_UP => [
                'account',
                'passwd',
                'confirm_passwd',
                'province',
                'city',
                'district',
                'mobile',
                'email',
                'verify_code',
            ],
            self::SCE_CHECK_ACCOUNT => [
                'account'
            ],
        ];
    }

    public function rules()
    {
        return [

            [
                ['account','passwd', 'confirm_passwd', 'province', 'city', 'district', 'mobile', 'email', 'verify_code'],
                'required',
                'message' => 9001,
            ],
            [
                ['account'],
                'exist',
                'targetClass' => CustomUserRegistercodeAR::className(),
                'targetAttribute' => ['account' => 'account', 'used' => 'used'],
                'filter'=>['is_available'=>RegisterCode::STATUS_AVAILABLE],
                'message' => 3161,
            ],
            [
                'passwd',
                'string',
                'length' => [8, 100],
                'tooShort' => 3162,
                'tooLong' => 3162,
                'message' => 3162,
            ],
            [
                ['confirm_passwd'],
                'required',
                'requiredValue' => $this->passwd,
                'message' => 3163,
            ],
            [
                ['district'],
                'common\validators\district\DistrictValidator',
                'province' => $this->province,
                'city' => $this->city,
                'message' => 3165,
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
                'common\validators\custom\AccountMobileValidator',
                'message' => 3257,
            ],
            [
                ['email'],
                'email',
                'message' => 3168,
            ],

        ];
    }

    //验证账户是否存在
    public function checkAccountStatus()
    {
        return true;
    }

    public function signUp()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $userIdentity = AccountHandler::create([
                'account' => new RegisterCode(['account' => $this->account]),
                'passwd' => $this->passwd,
                'district' => new District([
                    'districtId' => $this->district,
                    'cityId' => $this->city,
                    'provinceId' => $this->province,
                ]),
                'mobile' => $this->mobile,
                'email' => $this->email,
            ]);
            $couponSender = new \custom\models\parts\temp\SendCouponAfterRegister\CouponSender;
            $couponSender->sendTo($userIdentity->account);
            $transaction->commit();
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('signUp', 3169);
            return false;
        }
        if(Yii::$app->user->login($userIdentity)){
            return ['url' => '/'];
        }else{
            $this->addError('signUp', 3170);
            return false;
        }
    }

    protected function getUsed()
    {
        return RegisterCode::STATUS_UNUSED;
    }


}
