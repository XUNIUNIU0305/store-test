<?php
namespace api\models;

use Yii;
use common\models\Model;
use common\ActiveRecord\CustomUserAR;
use custom\models\parts\wechat\Wechat;
use common\models\parts\wechat\WechatUser;
use common\ActiveRecord\WechatUserBindAR;

class CustomModel extends Model{

    public $account;
    public $mobile;
    public $passwd;

    public $code;

    const SCE_VALIDATE_ACCOUNT = 'validate_account';
    const SCE_ACHIEVE_ACCOUNT_BY_WECHAT_USER = 'achieve_account_by_wechat_user';

    public function scenarios(){
        return [
            self::SCE_VALIDATE_ACCOUNT => [
                'account',
                'mobile',
                'passwd',
            ],
            self::SCE_ACHIEVE_ACCOUNT_BY_WECHAT_USER => [
                'code',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['code'],
                'required',
                'message' => 9001,
            ],
            [
                ['code'],
                'string',
                'message' => 9002,
            ],
        ];
    }

    public function achieveAccountByWechatUser(){
        $wechat = new Wechat([
            'site' => Wechat::SITE_CUSTOM,
            'appId' => Yii::$app->params['WECHAT_Public_Appid'],
            'appSecret' => Yii::$app->params['WECHAT_Public_Appsecret'],
        ]);
        if(($accessToken = $wechat->getAccessToken($this->code)) && isset($accessToken['access_token']) && isset($accessToken['unionid'])){
            try{
                $wechatUser = new WechatUser([
                    'unionId' => $accessToken['unionid'],
                ]);
                $userId = WechatUserBindAR::findOne([
                    'wechat_user_id' => $wechatUser->id,
                ])->user_id;
                return [
                    'account' => CustomUserAR::findOne($userId)->account,
                ];
            }catch(\Exception $e){
                $this->addError('achieveAccountByWechatUser', 7132);
                return false;
            }
        }else{
            $this->addError('achieveAccountByWechatUser', 7131);
            return false;
        }
    }

    public function validateAccount(){
        if(empty($this->passwd)){
            $this->addError('validateAccount', 9001);
            return false;
        }
        if($this->account){
            $user = CustomUserAR::findOne([
                'account' => $this->account,
            ]);
        }elseif(strlen($this->mobile) == 11){
            $user = CustomUserAR::findOne([
                'mobile' => $this->mobile,
            ]);
        }else{
            $this->addError('validateAccount', 9001);
            return false;
        }
        if(!$user){
            $this->addError('validateAccount', 7120);
            return false;
        }
        if($user && Yii::$app->security->validatePassword($this->passwd, $user->passwd)){
            if($user->status == 1){
                $this->addError('validateAccount', 7121);
                return false;
            }else{
                return [
                    'account' => $user->account,
                    'mobile' => $user->mobile ? : '',
                ];
            }
        }else{
            $this->addError('validateAccount', 7122);
            return false;
        }
    }
}
