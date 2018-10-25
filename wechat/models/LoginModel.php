<?php
/**
 * User: JiangYi
 * Date: 2017/5/22
 * Time: 11:56
 * Desc:用户登录相关操作
 */

namespace wechat\models;

use Yii;

class LoginModel extends \custom\models\LoginModel
{
    const SCE_SIGN_OUT="sign_out";


    public $ref;

    public function scenarios()
    {
        $scenario=[
            self::SCE_LOGIN=>['account','passwd'],
            self::SCE_SIGN_OUT=>['ref'],
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

    public static function saveWechatCode(){
        if($code = Yii::$app->request->get('code', false) ? : Yii::$app->session->get('__wechat_code', false)){
            if(!Yii::$app->session->get('__wechat_code', false)){
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

}
