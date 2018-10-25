<?php
namespace common\models\parts\trade\recharge\wechat;

use Yii;
use Exception;
use yii\base\Object;
use common\models\parts\trade\recharge\wechat\data\WxPayUnifiedOrder;
use common\models\parts\trade\recharge\wechat\data\WxPayResults;
use common\models\parts\trade\recharge\wechat\data\WxPayOrderQuery;

class WxPayApi extends Object{

    public static function unifiedOrder(WxPayUnifiedOrder $obj, $timeOut = 6){
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        if(!$obj->isOutTradeNoSet() ||
            !$obj->isBodySet() ||
            !$obj->isTotalFeeSet() ||
            !$obj->isTradeTypeSet()
        ){
            throw new Exception('param missed');
        }
        if($obj->getTradeType() == 'JSAPI' && !$obj->isOpenIdSet()){
            throw new Exception('param missed');
        }
        if($obj->getTradeType() == 'NATIVE' && !$obj->isProductIdSet()){
            throw new Exception('param missed');
        }
        if(!$obj->isNotifyUrlSet()){
            $obj->setNotifyUrl(Yii::$app->params['WECHAT_Notify_Url']);
        }
        $obj->setAppId(Yii::$app->params['WECHAT_Public_Appid']);
        $obj->setMchId(Yii::$app->params['WECHAT_Mchid']);
        $obj->setSpbillCreateIp(Yii::$app->request->userIP);
        $obj->setNonceStr(self::getNonceStr());
        $obj->setSign();
        $xml = $obj->toXml();
        $startTimeStamp = self::getMillisecond();
        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::initialize($response);
        //self::reportCostTime($url, $startTimeStamp, $result); //上报请求花费时间
        return $result;
    }

    public static function orderQuery(WxPayOrderQuery $obj, $timeout = 6){
        $url = 'https://api.mch.weixin.qq.com/pay/orderquery';
        if(!$obj->IsOutTradeNoSet() && !$obj->IsTransactionIdSet()){
            throw new Exception('param missed');
        }
        $obj->SetAppid(Yii::$app->params['WECHAT_Public_Appid']);
        $obj->SetMchId(Yii::$app->params['WECHAT_Mchid']);
        $obj->SetNonceStr(self::getNonceStr());
        $obj->setSign();
        $xml = $obj->toXml();
        $startTimeStamp = self::getMillisecond();
        $response = self::postXmlCurl($xml, $url, false, $timeout);
        $result = WxPayResults::initialize($response);
        return $result;
    }

    public static function getNonceStr($length = 32){
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $result = '';
        for($i = 0; $i < $length; $i++){
            $result .= substr($chars, rand(0, strlen($chars) - 1), 1);
        }
        return $result;
    }

    public static function notify($callback, &$msg){
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        try{
            $result = WxPayResults::init($xml);
        }catch(Exception $e){
            $msg = $e->getMessage();
            return false;
        }
        return call_user_func($callback, $result);
    }

    private static function postXmlCurl($xml, $url, $useCert = false, $second = 30){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        //代理设置，忽略

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //证书设置，忽略
        //if($useCert == true){
        
        //}

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $result = curl_exec($ch);
        if($result){
            curl_close($ch);
            return $result;
        }else{
            $error = curl_errno($ch);
            curl_close($ch);
            throw new Exception('curl failed: ' . $error);
        }
    }

    private static function getMillisecond(){
        $time = explode(' ', microtime());
        $time = $time[1] . ($time[0] * 1000);
        $time = explode('.', $time);
        return $time[0];
    }
}
