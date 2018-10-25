<?php
namespace common\models\parts\wechat;

use Yii;
use Curl\Curl;
use yii\base\Object;
use yii\base\InvalidConfigException;
use yii\base\InvalidCallException;
use common\components\handler\Handler;
use common\ActiveRecord\WechatUserAR;
use common\ActiveRecord\WechatUserBindAR;

abstract class WechatAbstract extends Object{

    const SITE_CUSTOM = 1;
    const SITE_SUPPLY = 2;
    const SITE_ADMIN = 3;

    const ACTION_LOGIN = 1;
    const ACTION_BIND = 2;

    public $site;

    public $appId;
    public $appSecret;

    private $_appId;
    private $_appSecret;

    protected $redirectUrl;

    protected $wechatScanUrl = 'https://open.weixin.qq.com/connect/qrconnect';
    protected $wechatAccessTokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    protected $wechatUserInfoUrl = 'https://api.weixin.qq.com/sns/userinfo';

    public function init(){
        if(!in_array($this->site, self::getSiteList()))throw new InvalidConfigException('undefined site');
        $this->_appId = $this->appId ?? Yii::$app->params['WECHAT_Open_Appid'];
        $this->_appSecret = $this->appSecret ?? Yii::$app->params['WECHAT_Open_Appsecret'];
        $this->redirectUrl = Yii::$app->params['API_Hostname'] . '/wechat/verify';
    }

    abstract protected function getUserActiveRecord();

    abstract protected function getUserIdentity();

    public function login(WechatUser $user){
        /* custom站登陆时判断status字段，0可登陆；admin与supply站未配置，这两站需使用时重新配置 */
        if(!in_array($this->site, [self::SITE_CUSTOM]))return false;
        /* 方案一：两站全部配置status字段，并设置字段值[0]可登陆
         * 方案二：添加抽象方法定义状态字段，并设置可登陆字段值
         */


        $userId = Yii::$app->RQ->AR(new WechatUserBindAR)->scalar([
            'select' => ['user_id'],
            'where' => [
                'wechat_user_id' => $user->id,
                'site' => $this->site,
            ],
        ]);


        if(!$userId)return false;

        $userIdentity = static::getUserIdentity()::findOne([
            'id' =>$userId,
            'status' => 0,
        ]);

        if(!$userIdentity)return false;
        if(Yii::$app->user->login($userIdentity)){
            return true;
        }else{
            //跟踪登录失败请求头
            try{
                Yii::$app->RQ->AR(new \common\ActiveRecord\LoginFailureLogAR)->insert([
                    'custom_user_id' => $userIdentity->id,
                    'request_header' => serialize(\common\components\handler\VisitorHandler::collectRequestHeader()),
                    'wechat_code' => Yii::$app->session->get('__wechat_code', '-1'),
                    'wechat_openid' => Yii::$app->session->get('__wechat_public_openid', '-1'),
                    'tag' => '2',
                ]);
            }catch(\Exception $e){}
            //结束
            return false;
        }
    }

    public function bind(WechatUser $user, $userId){
        if(static::getUserActiveRecord()::findOne($userId)){
            if(Yii::$app->RQ->AR(new WechatUserBindAR)->scalar([
                'select' => ['id'],
                'where' => [
                    'wechat_user_id' => $user->id,
                    'site' => $this->site,
                ],
            ]))return false;
            return Yii::$app->RQ->AR(new WechatUserBindAR)->insert([
                'user_id' => $userId,
                'wechat_user_id' => $user->id,
                'site' => $this->site,
                'bind_datetime' => Yii::$app->time->fullDate,
                'bind_unixtime' => Yii::$app->time->unixTime,
            ], false);
        }else{
            return false;
        }
    }

    public function unbind(WechatUser $user){
        $bindedData = WechatUserBindAR::findOne([
            'wechat_user_id' => $user->id,
            'site' => $this->site,
        ]);
        if(!$bindedData)return true;
        return $bindedData->delete();
    }

    public function getLoginScanUrl(){
        return $this->getScanUrl(self::ACTION_LOGIN);
    }

    public function getBindScanUrl(){
        return $this->getScanUrl(self::ACTION_BIND);
    }

    public function getScanUrl($action){
        switch($action){
            case self::ACTION_LOGIN:
                $stateParam = [
                    'action' => self::ACTION_LOGIN,
                    'site' => $this->site,
                ];
                break;

            case self::ACTION_BIND:
                if(Yii::$app->user->isGuest)throw new InvalidCallException('the user must be logined before do binding action');
                $stateParam = [
                    'action' => self::ACTION_BIND,
                    'site' => $this->site,
                    'userid' => Yii::$app->user->id,
                ];
                break;

            default:
                throw new InvalidCallException('unknown action');
        }
        $urlParams = [
            'appid' => $this->_appId,
            'redirect_uri' => urlencode($this->redirectUrl),
            'response_type' => 'code',
            'scope' => 'snsapi_login',
            'state' => urlencode((new UrlParamCrypt)->encrypt($stateParam)),
        ];
        return ($this->wechatScanUrl . '?' . Handler::implodeUrlParams($urlParams, false));
    }

    public static function getCode(){
        if(!$code = Yii::$app->request->get('code', false))return false;
        if(!$state = Yii::$app->request->get('state', false))return false;
        if(!$state = (new UrlParamCrypt)->decrypt($state))return false;
        return $code;
    }

    public function getAccessToken($code){
        $urlParams = [
            'appid' => $this->_appId,
            'secret' => $this->_appSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];
        $curl = new Curl();
        $curl->setDefaultJsonDecoder(true);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->get($this->wechatAccessTokenUrl, $urlParams);
        if($curl->error){
            return false;
        }else{
            return json_decode($curl->response, true);
        }
    }

    public function getWechatUser($accessToken, $openId, $addIfNotExist = false){
        $urlParams = [
            'access_token' => $accessToken,
            'openid' => $openId,
        ];
        $curl = new Curl();
        $curl->setDefaultJsonDecoder(true);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->get($this->wechatUserInfoUrl, $urlParams);
        if($curl->error){
            return false;
        }else{
            $userInfo = json_decode($curl->response, true);
            if(!$wechatUserId = Yii::$app->RQ->AR(new WechatUserAR)->scalar([
                'select' => 'id',
                'where' => [
                    'unionid_hash' => Handler::generateBKDRHash($userInfo['unionid']),
                    'unionid' => $userInfo['unionid'],
                ],
            ])){
                if($addIfNotExist){
                    $wechatUserId = Yii::$app->RQ->AR(new WechatUserAR)->insert([
                        'openid' => $userInfo['openid'] ?? '',
                        'nickname' => $userInfo['nickname'] ?? '',
                        'sex' => $userInfo['sex'] ?? '',
                        'province' => $userInfo['province'] ?? '',
                        'city' => $userInfo['city'] ?? '',
                        'country' => $userInfo['province'] ?? '',
                        'headimgurl' => $userInfo['headimgurl'] ?? '',
                        'privilege' => is_array($userInfo['privilege']) ? serialize($userInfo['privilege']) : '',
                        'unionid' => $userInfo['unionid'],
                        'unionid_hash' => Handler::generateBKDRHash($userInfo['unionid'], false),
                    ]);
                }else{
                    return false;
                }
            }else{
                Yii::$app->RQ->AR(WechatUserAR::findOne([
                    'unionid_hash' => Handler::generateBKDRHash($userInfo['unionid']),
                    'unionid' => $userInfo['unionid'],
                ]))->update([
                    'openid' => $userInfo['openid'] ?? '',
                    'nickname' => $userInfo['nickname'] ?? '',
                    'sex' => $userInfo['sex'] ?? '',
                    'province' => $userInfo['province'] ?? '',
                    'city' => $userInfo['city'] ?? '',
                    'country' => $userInfo['province'] ?? '',
                    'headimgurl' => $userInfo['headimgurl'] ?? '',
                    'privilege' => is_array($userInfo['privilege']) ? serialize($userInfo['privilege']) : '',
                ], false);
            }
            if(!$wechatUserId)return false;
            return new WechatUser(['id' => $wechatUserId]);
        }
    }

    public static function getSiteList(){
        return [
            self::SITE_CUSTOM,
            self::SITE_SUPPLY,
            self::SITE_ADMIN,
        ];
    }

    public static function getActionList(){
        return [
            self::ACTION_LOGIN,
            self::ACTION_BIND,
        ];
    }
}
