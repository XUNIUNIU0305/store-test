<?php
namespace mobile\components;

use Yii;
use yii\web\ForbiddenHttpException;
use custom\models\parts\wechat\Wechat;

class User extends \yii\web\User{

    private $_wechat_code;
    private $_wechat_openid;

    protected function beforeLogin($identity, $cookieBased, $duration){
        parent::beforeLogin($identity, $cookieBased, $duration);
        if(Yii::$app->session->get('__wechat_public_openid', false)){
            $this->_wechat_openid = Yii::$app->session->get('__wechat_public_openid');
            return true;
        }else{
            if(!$code = Yii::$app->session->get('__wechat_code', false)){
                return false;
            }
            $wechat = new Wechat([
                'site' => Wechat::SITE_CUSTOM,
                'appId' => Yii::$app->params['WECHAT_Public_Appid'],
                'appSecret' => Yii::$app->params['WECHAT_Public_Appsecret'],
            ]);
            $accessToken = $wechat->getAccessToken($code);
            if(!isset($accessToken['openid'])){
                return false;
            }
            Yii::$app->session->set('__wechat_public_openid', $accessToken['openid']);
            $this->_wechat_openid = $accessToken['openid'];
            return true;
        }
    }
 
    protected function afterLogin($identity, $cookieBased, $duration){
        parent::afterLogin($identity, $cookieBased, $duration);
        if(!Yii::$app->session->get('__wechat_code', false) || !Yii::$app->session->get('__wechat_public_openid', false)){
            try{
                if($this->_wechat_openid){
                    Yii::$app->session->set('__wechat_public_openid', $this->_wechat_openid);
                }
                Yii::$app->RQ->AR(new \common\ActiveRecord\LoginFailureLogAR)->insert([
                    'custom_user_id' => $identity->id,
                    'request_header' => Yii::$app->session->get(Yii::$app->params['LOGIN_KEY'], '-1') . '  ' . Yii::$app->session->get('__wechat_public_openid', '-1'),
                    'wechat_code' => Yii::$app->session->get('__wechat_code', '-1'),
                    'wechat_openid' => Yii::$app->session->get('__wechat_public_openid', '-1'),
                    'tag' => '4',
                ]);
            }catch(\Exception $e){}
        }
    }
}
