<?php
namespace wechat\models;

use common\models\Model;
use common\models\parts\wechat\WechatUser;
use custom\models\parts\wechat\Wechat;
use Yii;


class AuthorizeModel extends Model
{

    const SCE_AUTHORIZE = "authorize";

    public $code;
    private $unionId;


    const SCOPE_TYPE_BASE = "snsapi_base";//配置授权方式，静默授权
    const SCOPE_TYPE_USER = "snsapi_userinfo";//提示用户授权

    /**
     * Author:JiangYi
     * Date:2017/5/19
     * Desc:配置场景需要参数
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCE_AUTHORIZE => ['code'],
        ];
    }

    /**
     * Author:JiangYi
     * Date:2017/5/19
     * Desc:配置验证规则
     * @return array
     */
    public function rules()
    {
        return [
            [
                ['code'],
                'default',
                'value' => '',
            ],

        ];
    }


    /**
     * Author:JiangYi
     * Date:2017/05/18
     * Desc:执行授权操作,当参数code 与session('unionId')同时为空时，返回获取code路径地址，直接使用unionId登录     *
     * @return bool|string
     *
     */
    public function authorize()
    {
        // Yii::$app->session->set(Yii::$app->params['LOGIN_KEY'],"o-7q3065T5V15bUu5JiTsVpL8iU0");
        $this->unionId = Yii::$app->session->get(Yii::$app->params['LOGIN_KEY'], false);
        //未带参数code则跳转至授权
        try {
            if (empty($this->code) && !$this->unionId) {

                return $this->resetUrl(self::SCOPE_TYPE_BASE);
            } else {

                if (!empty($this->code)) {
                    //获取token及相关数据
                    $wechat = new Wechat([
                        'site' => Wechat::SITE_CUSTOM,
                        'appId' => Yii::$app->params['WECHAT_Public_Appid'],
                        'appSecret' => Yii::$app->params['WECHAT_Public_Appsecret'],
                    ]);
                    $accessToken = $wechat->getAccessToken($this->code);
                    if ($accessToken && isset($accessToken['access_token']) && isset($accessToken['unionid'])) {
                        Yii::$app->session->set(Yii::$app->params['LOGIN_KEY'], $accessToken['unionid']);
                        $wechatUser = new WechatUser(['unionId' => $accessToken['unionid']]);
                        if ($this->login($wechatUser)) {
                            return true;
                        }

                        return false;
                    } else {
                        return $this->resetUrl(self::SCOPE_TYPE_USER);
                    }
                }elseif ($this->unionId) {
                   //不需要获取用户数据，且已授权
                    $wechatUser = new WechatUser(['unionId' => $this->unionId]);
                    if ($this->login($wechatUser)) {
                        return true;
                    }

                    return false;
                }
            }
        } catch (\Exception $e) {

            return false;
        }

    }


    /**
     * Author:JiangYi
     * Date:2017/05/18
     * Desc:根据系统配置参数，重组授权URL
     *
     * @return string
     *
     */
    private function resetUrl($scopeType)
    {
        $redirect = urlencode(sprintf('http://wechat.9daye.com.cn/api/9daye?m=openid_code&url=%s', urlencode(Yii::$app->request->getHostInfo() . Yii::$app->request->url)));
        $url = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=123#wechat_redirect', Yii::$app->params['WECHAT_Public_Appid'], $redirect, $scopeType);
        return $url;
    }


    /**
     * Author:JiangYi
     * Date:2017/05/18
     * Desc:微信授权获取用户执行登录操作
     *
     * @param WechatUser $wechatUser
     * @return bool
     *
     */
    private function login(WechatUser $wechatUser)
    {
        if ((new Wechat([
            'site' => Wechat::SITE_CUSTOM,
            'appId' => Yii::$app->params['WECHAT_Public_Appid'],
            'appSecret' => Yii::$app->params['WECHAT_Public_Appsecret'],
        ]))->login($wechatUser)) {
            return true;
        }
        return false;
    }


}
