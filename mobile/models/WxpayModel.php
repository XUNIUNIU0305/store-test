<?php
namespace mobile\models;

use Yii;
use common\models\Model;
use common\models\parts\trade\recharge\wechat\UrlParamCrypt;
use common\models\parts\trade\recharge\wechat\data\WxPayJsApiPay;

class WxpayModel extends Model{

    public static function getPayParams(){
        if($q = Yii::$app->request->get('q', false)){
            $urlParamCrypt = new UrlParamCrypt;
            try{
                if($params = $urlParamCrypt->decrypt($q)){
                    $wxJsPay = new WxPayJsApiPay;
                    $wxJsPay->setAppId($params['appid']);
                    $wxJsPay->setTimeStamp(Yii::$app->time->unixTime);
                    $wxJsPay->setNonceStr($params['nonce_str']);
                    $wxJsPay->setPackage("prepay_id={$params['prepay_id']}");
                    $wxJsPay->setSignType('MD5');
                    $wxJsPay->setSign();
                    return [
                        'appid' => $wxJsPay->getAppId(),
                        'time_stamp' => $wxJsPay->getTimeStamp(),
                        'nonce_str' => $wxJsPay->getNonceStr(),
                        'package' => $wxJsPay->getPackage(),
                        'sign_type' => $wxJsPay->getSignType(),
                        'pay_sign' => $wxJsPay->getSign(),
                        'total_fee' => (new \custom\models\parts\UrlParamCrypt)->encrypt($params['total_fee']),
                    ];
                }else{
                    return false;
                }
            }catch(\Exception $e){
                return false;
            }
        }else{
            return false;
        }
    }
}
