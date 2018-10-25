<?php
/**
 * User: JiangYi
 * Date: 2017/5/22
 * Time: 11:56
 * Desc:用户登录相关操作
 */

namespace mobile\modules\member\models;


use Yii;
use custom\models\parts\UserIdentity;

class LoginModel extends \custom\models\LoginModel
{
    const SCE_MOBILE_LOGIN="mobile_login";
    const SCE_SIGN_OUT="sign_out";
    const SCE_IS_RETURN="return_url";


    public $ref;
    public $return_url;

    public function scenarios()
    {
        $scenario=[
            self::SCE_MOBILE_LOGIN=>['account','passwd'],
            self::SCE_SIGN_OUT=>['ref'],
            self::SCE_IS_RETURN=>['return_url'],
        ];
        return array_merge(parent::scenarios(),$scenario);
    }

    public function rules()
    {
        $rules=[
            [
                ['redirect'],
                'string',
                'length'=>[1,1000],
                'message' => 3021,
            ],

            [
                ['return_url'] ,
                'url',
                'message'=>9002,
            ]
        ];
        return array_merge(parent::rules(),$rules);
    }


    /**
     * Author:JiangYi
     * Date:2017/5/27
     * Desc:用户退出登录
     * @return bool
     */
    public function signOut(){
        if(Yii::$app->user->logout()){
            echo $this->ref;
            return empty($this->ref)?true:$this->ref;
        }else{
            $this->addError('logout', 3021);
            return false;
        }
    }


    public function mobileLogin(){

        if(strlen($this->account)==9) {
            //账号登录
            $userIdentity = UserIdentity::findOne([
                'account' => $this->account,
                'status' => 0,
            ]);
        }elseif(strlen($this->account)==11){
            //手机登录
            $userIdentity = UserIdentity::findOne([
                'mobile' => $this->account,
                'status' => 0,
            ]);
        }else{
            $this->addError('login',3012);
            return false;
        }
        if($userIdentity && Yii::$app->getSecurity()->validatePassword($this->passwd, $userIdentity->passwd)){
            if(Yii::$app->user->login($userIdentity)){
                return Yii::$app->user->returnUrl ? ['url'=> Yii::$app->user->returnUrl]: true;
            }else{
                //跟踪登录失败请求头
                try{
                    Yii::$app->RQ->AR(new \common\ActiveRecord\LoginFailureLogAR)->insert([
                        'custom_user_id' => $userIdentity->id,
                        'request_header' => serialize(\common\components\handler\VisitorHandler::collectRequestHeader()),
                        'wechat_code' => Yii::$app->session->get('__wechat_code', '-1'),
                        'wechat_openid' => Yii::$app->session->get('__wechat_public_openid', '-1'),
                        'tag' => '1',
                    ]);
                }catch(\Exception $e){}
                //结束
                $this->addError('login', 3013);
                return false;
            }
        }else{
            $this->addError('login', 3012);
            return false;
        }
    }

    public static function saveWechatCode(){
        if($code = Yii::$app->request->get('code', false) ? : Yii::$app->session->get('__wechat_code', false)){
            if(Yii::$app->session->get('__wechat_code', false) != $code){
                Yii::$app->session->set('__wechat_code', $code);
            }
            return true;
        }else{
            return false;
        }
    }

    public static function generateLoginUrl(){
        $redirect = urlencode('http://wechat.9daye.com.cn/api/9daye?m=openid_code&url=' . urlencode(Yii::$app->request->hostInfo . Yii::$app->request->url));
        $url = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=123#wechat_redirect', Yii::$app->params['WECHAT_Public_Appid'], $redirect, 'snsapi_base');
        return $url;
    }

    public function returnUrl(){
        if(Yii::$app->user->isGuest && $this->return_url){
            Yii::$app->user->setReturnUrl($this->return_url);
        }
        return [
            'status'=>Yii::$app->user->isGuest ? 0 : 1,
            'url'=>Yii::$app->user->loginUrl
        ];
    }
}
