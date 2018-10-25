<?php
namespace common\models\parts\trade\recharge\alipay;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;
use common\ActiveRecord\AlipayNotifyLogAR;
use common\models\RapidQuery;

class Alipay extends Object{

    public $config;

    protected $alipayConfig = [
        'partner' => '',
        'seller_id' => '',
        'notify_url' => '',
        'return_url' => '',
        'sign_type' => '',
        '_input_charset' => '',
        'transport' => '',
        'payment_type' => '',
        'service' => '',
    ];
    protected $gateway = 'https://mapi.alipay.com/gateway.do?';
    protected $httpsVerifyUrl = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
    protected $httpVerifyUrl = 'http://notify.alipay.com/trade/notify_query.do?';
    protected $RSAPrivateKey;
    protected $AlipayPublicKey;
    protected $AlipayCacert;

    private $_logId;

    public function init(){
        $this->alipayConfig = array_merge($this->alipayConfig, Yii::$app->params['ALIPAY_Config']);
        if(is_array($this->config)){
            $this->alipayConfig = array_merge($this->alipayConfig, $this->config);
        }
        $this->RSAPrivateKey = Yii::$app->params['RSA_Private_Key'];
        $this->AlipayPublicKey = Yii::$app->params['ALIPAY_Public_Key'];
        $this->AlipayCacert = Yii::$app->params['ALIPAY_Cacert'];
    }

    /**
     * 生成充值路径
     *
     * @return string
     */
    public function generatePayUrl(array $config = null){
        if(is_array($config)){
            $config = array_merge($this->alipayConfig, $config);
        }else{
            $config = $this->alipayConfig;
        }
        $requestParam = $this->generateRequestParam($config);
        return ($this->gateway . $this->generateLinkString($requestParam, true));
    }

    /**
     * 验证ALI通知合法性
     *
     * @return boolean
     */
    public function verifyNotify(){
        if(empty($_POST)){
            return false;
        }else{
            $isSign = $this->getSignVerify('POST');
            $responseTxt = 'false';
            if(!empty($_POST['notify_id']))$responseTxt = $this->getResponse($_POST['notify_id']);
            if(preg_match('/true$/i', $responseTxt) && $isSign){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * 验证ALI跳转合法性
     *
     * @return boolean
     */
    public function verifyReturn(){
        if(empty($_GET)){
            return false;
        }else{
            $isSign = $this->getSignVerify('GET');
            $responseTxt = 'false';
            if(!empty($_GET['notify_id']))$responseTxt = $this->getResponse($_GET['notify_id']);
            if(preg_match('/true$/i', $responseTxt) && $isSign){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * 获取ALI通知路径
     *
     * @return string
     */
    public static function getNotifyUrl(){
        return Yii::$app->params['ALIPAY_Notify_Url'];
    }

    /**
     * 获取充值记录ID
     *
     * @return integer
     */
    public function getLogId(){
        return $this->_logId;
    }

    /**
     * 写入充值记录
     *
     * @return integer|false
     */
    public function writeLog(){
        //日志全部写入数据库
        if(/*YII_ENV != 'prod'*/true){
            $fields = [
                'notify_time' => null,
                'notify_type' => '',
                'notify_id' => '',
                'sign_type' => '',
                'sign' => '',
                'out_trade_no' => 0,
                'subject' => '',
                'payment_type' => '',
                'trade_no' => '',
                'trade_status' => '',
                'gmt_create' => null,
                'gmt_payment' => null,
                'gmt_close' => null,
                'refund_status' => '',
                'gmt_refund' => null,
                'seller_email' => '',
                'buyer_email' => '',
                'seller_id' => '',
                'buyer_id' => '',
                'price' => 0,
                'total_fee' => 0,
                'quantity' => 0,
                'body' => '',
                'discount' => 0,
                'is_total_fee_adjust' => '',
                'use_coupon' => '',
                'extra_common_param' => '',
                'business_scene' => '',
            ];
            foreach($fields as $k => $val){
                $fields[$k] = isset($_POST[$k]) ? urldecode($_POST[$k]) : $val;
            }
            if((new RapidQuery(new AlipayNotifyLogAR))->insert($fields)){
                return Yii::$app->db->lastInsertId;
            }else{
                return false;
            }
        }else{
            $message = var_export($_POST, true);
            $data = '执行时间：' . Yii::$app->time->fullDate . "\n" . $message . "\n\n\n";
            return file_put_contents(__DIR__ . '/log.txt', $data, FILE_APPEND);
        }
    }

    /**
     * 验证ALI通知合法性
     *
     * @return string
     */
    protected function getResponse($notifyId){
        $transport = $this->alipayConfig['transport'];
        $partner = $this->alipayConfig['partner'];
        if($transport == 'https'){
            $verifyUrl = $this->httpsVerifyUrl;
        }else{
            $verifyUrl = $this->httpVerifyUrl;
        }
        $verifyUrl = $verifyUrl . 'partner=' . $partner . '&notify_id=' . $notifyId;
        $responseTxt = $this->getHttpResponseGET($verifyUrl, $this->AlipayCacert);
        return $responseTxt;
    }

    /**
     * 获取ALI合法性验证结果
     *
     * @return string
     */
    protected function getHttpResponseGET($url, $cacertPath){
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_CAINFO, $cacertPath);
        $responseTxt = curl_exec($curl);
        curl_close($curl);
        return $responseTxt;
    }

    /**
     * ALI回调签名验证
     *
     * @return boolean
     */
    protected function getSignVerify($type){
        switch($type){
            case 'POST':
                $params = $_POST;
                $sign = $_POST['sign'];
                break;

            case 'GET':
                $params = $_GET;
                $sign = $_GET['sign'];
                break;

            default:
                return false;
        }
        $params = $this->argsSort($this->filterEmptyParam($params));
        $string = $this->generateLinkString($params);
        switch($this->alipayConfig['sign_type']){
            case 'RSA':
                $isSign = $this->rsaVerify($string, $this->AlipayPublicKey, $sign);
                break;

            default:
                $isSign = false;
        }
        return $isSign;
    }

    /**
     * RSA证书验证
     *
     * @return boolean
     */
    protected function rsaVerify($data, $publicKey, $sign){
        if($resource = openssl_get_publickey($publicKey)){
            $result = (bool)openssl_verify($data, base64_decode($sign), $resource);
            openssl_free_key($resource);
            return $result;
        }else{
            throw new InvalidConfigException('Incorrect public key');
        }
    }

    /**
     * 生成充值请求参数
     *
     * @return array
     */
    protected function generateRequestParam(array $params){
        $params = $this->argsSort($this->filterEmptyParam($params));
        $params['sign'] = $this->signRequestParam($params);
        $params['sign_type'] = $this->alipayConfig['sign_type'];
        return $params;
    }

    /**
     * 对请求参数生成签名
     *
     * @return string
     */
    protected function signRequestParam(array $params){
        switch($this->alipayConfig['sign_type']){
            case 'RSA':
                $string = $this->generateLinkString($params);
                $sign = $this->rsaSign($string, $this->RSAPrivateKey);
                break;

            default:
                $sign = '';
        }
        return $sign;
    }

    /**
     * 执行RSA签名
     *
     * @return string
     */
    protected function rsaSign($data, $privateKey){
        if($resource = openssl_pkey_get_private($privateKey)){
            openssl_sign($data, $sign, $resource);
            openssl_free_key($resource);
            return base64_encode($sign);
        }else{
            throw new InvalidConfigException('Incorrect private key');
        }
    }

    /**
     * 生成路径字符串
     *
     * @return string
     */
    protected function generateLinkString(array $params, $urlencode = false){
        $completeParams = [];
        foreach($params as $k => $v){
            $completeParams[] = $k . '=' . ($urlencode ? urlencode($v) : $v);
        }
        return implode('&', $completeParams);
    }

    /**
     * 过滤空参数
     *
     * @return array
     */
    protected function filterEmptyParam(array $params){
        return array_filter($params, function($v, $k){
            return (($k == 'sign' || $k == 'sign_type' || empty($v)) ? false : true);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * 对数组重排序
     *
     * @return array
     */
    protected function argsSort(array $param){
        ksort($param);
        reset($param);
        return $param;
    }
}
