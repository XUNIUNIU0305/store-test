<?php
namespace custom\models;

use Yii;
use common\models\Model;
use custom\models\parts\wechat\Wechat;
use common\models\parts\wechat\WechatUser;
use common\models\parts\wechat\UrlParamCrypt;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\WechatUserBindAR;

class WechatModel extends Model{

    const SCE_USER_HANDLE = 'user_handle';
    const SCE_BIND_URL = 'bind_url';
    const SCE_UNBIND_WECHAT_ACCOUNT = 'unbind_wechat_account';

    public $state;
    public $code;
    public $passwd;

    public function scenarios(){
        return [
            self::SCE_USER_HANDLE => [
                'state',
                'code',
            ],
            self::SCE_BIND_URL => [
                'passwd',
            ],
            self::SCE_UNBIND_WECHAT_ACCOUNT => [
                'passwd',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['state', 'code', 'passwd'],
                'required',
                'message' => 9001,
            ],
        ];
    }

    public function userHandle(){
        if(($state = (new UrlParamCrypt)->decrypt(urldecode($this->state))) && isset($state['action']) && isset($state['site']) && $state['site'] == Wechat::SITE_CUSTOM){
            switch($state['action']){
            case Wechat::ACTION_LOGIN:
                $add = false;
                break;

            case Wechat::ACTION_BIND:
                if(isset($state['userid']) && CustomUserAR::findOne($state['userid'])){
                    $userId = $state['userid'];
                }else{
                    $this->addError('userHandle', 9002);
                    return false;
                }
                $add = true;
                break;

            default:
                $this->addError('userHandle', 9002);
                return false;
                break;
            }
        }else{
            $this->addError('userHandle', 9002);
            return false;
        }
        $wechat = new Wechat(['site' => Wechat::SITE_CUSTOM]);
        if(($accessToken = $wechat->getAccessToken($this->code)) && isset($accessToken['access_token']) && isset($accessToken['openid'])){
            $redirectUrl = Yii::$app->params['CUSTOM_Hostname'] . '/login';
            $exitMessage = <<<EOF
登陆失败，请确保该微信已绑定账号。
<script>
setTimeout(function(){
window.location.href = '{$redirectUrl}';
}, 5000);
</script>
EOF;
            if(!$wechatUser = $wechat->getWechatUser($accessToken['access_token'], $accessToken['openid'], $add)){
            exit($exitMessage);
            $this->addError('userHandle', 9002);
            return false;
            }
            switch($state['action']){
                case Wechat::ACTION_LOGIN:
                    if($wechat->login($wechatUser)){
                        return Yii::$app->params['CUSTOM_Hostname'] . Yii::$app->user->returnUrl;
                    }else{
                        exit($exitMessage);
                        $this->addError('userHandle', 9002);
                        return false;
                    }
                    break;

                case Wechat::ACTION_BIND:
                    if(!Yii::$app->session->getFlash('allow_bind_wechat_account', false)){
                        exit('未授权操作');
                    }
                    if($wechat->bind($wechatUser, $userId)){
                        return Yii::$app->params['CUSTOM_Hostname'] . '/account/wechat';
                    }else{
                        $redirectUrl = Yii::$app->params['CUSTOM_Hostname'] . '/account/wechat';
                        $exitMessage = <<<EOF
绑定失败，请确保该微信号未绑定账号。
<script>
    setTimeout(function(){
        window.location.href = '{$redirectUrl}';
    }, 5000);
</script>
EOF;
                        exit($exitMessage);
                        $this->addError('userHandle', 9002);
                        return false;
                    }
                    break;
            }
        }else{
            $this->addError('userHandle', 9002);
            return false;
        }
    }

    public static function getLoginUrl(){
        $url = (new Wechat(['site' => Wechat::SITE_CUSTOM]))->loginScanUrl;
        return [
            'url' => $url,
        ];
    }

    public function bindUrl(){
        if(!Yii::$app->user->isGuest && Yii::$app->security->validatePassword($this->passwd, Yii::$app->user->identity->passwd)){
            $url = (new Wechat(['site' => Wechat::SITE_CUSTOM]))->bindScanUrl;
            Yii::$app->session->setFlash('allow_bind_wechat_account', true);
            return [
                'url' => $url,
            ];
        }else{
            $this->addError('bindUrl', 3401);
            return false;
        }
    }

    public static function getWechatUserInfo(){
        if($wechatUserBind = WechatUserBindAR::findOne([
            'user_id' => Yii::$app->user->id,
            'site' => Wechat::SITE_CUSTOM,
        ])){
            $wechatUser = new WechatUser([
                'id' => $wechatUserBind->wechat_user_id,
            ]);
            return [
                'username' => $wechatUser->nickname,
                'head_img' => $wechatUser->headImageUrl,
                'bind_time' => $wechatUserBind->bind_datetime,
            ];
        }else{
            return [];
        }
    }

    public function unbindWechatAccount(){
        if(!Yii::$app->user->isGuest && Yii::$app->security->validatePassword($this->passwd, Yii::$app->user->identity->passwd)){
            $wechat = new Wechat([
                'site' => Wechat::SITE_CUSTOM,
            ]);
            if($wechat->unbind(Yii::$app->CustomUser->CurrentUser->wechatUser)){
                return true;
            }else{
                $this->addError('unbindWechatAccount', 3402);
                return false;
            }
        }else{
            $this->addError('bindUrl', 3401);
            return false;
        }
    }
}
