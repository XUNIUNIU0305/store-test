<?php
namespace common\models\parts\trade\recharge\wechat;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;
use common\models\parts\trade\recharge\wechat\data\WxPayUnifiedOrder;
use common\models\parts\trade\recharge\RechargeApply;
use common\models\parts\trade\recharge\wechat\data\WxPayResults;
use common\ActiveRecord\WxpayNotifyLogAR;

class Wechat extends Object{

    public $config;

    protected $payUrl;

    protected $notifyData;

    protected $wechatConfig = [
        'out_trade_no' => '',
        'body' => '',
        'total_fee' => '',
        'trade_type' => '',
        'user_type' => '',
        'pay_url' => '',
    ];

    public function verifyNotify(){
        if($notifyData = $this->getNotifyData()){
            return ($notifyData['return_code'] == 'SUCCESS');
        }else{
            return false;
        }
    }

    public function getNotifyData(){
        if(is_null($this->notifyData)){
            $xml = file_get_contents('php://input');
            try{
                $this->notifyData = WxPayResults::initialize($xml);
            }catch(\Exception $e){
                $this->notifyData = false;
            }
        }
        return $this->notifyData;
    }

    public function setNotifyData($data){
        $this->notifyData = $data;
        return $this;
    }

    public function clearNotifyData(){
        $this->notifyData = null;
        return $this;
    }

    public function writeLog($return = 'throw'){
        if(!$notifyData = $this->getNotifyData())return Yii::$app->EC->callback($return, 'no notify data');
        $logData = $this->generateLogData($notifyData);
        return Yii::$app->RQ->AR(new WxpayNotifyLogAR)->insert($logData, $return);
    }

    protected function generateLogData(array $notifyData){
        $defaultData = [
            'return_code' => null,
            'return_msg' => null,
            'appid' => null,
            'mch_id' => null,
            'device_info' => null,
            'nonce_str' => null,
            'sign' => null,
            'sign_type' => null,
            'result_code' => null,
            'err_code' => null,
            'err_code_des' => null,
            'openid' => null,
            'is_subscribe' => null,
            'trade_type' => null,
            'bank_type' => null,
            'total_fee' => null,
            'settlement_total_fee' => null,
            'fee_type' => null,
            'cash_fee' => null,
            'cash_fee_type' => null,
            'coupon_fee' => null,
            'coupon_count' => null,
            'transaction_id' => null,
            'out_trade_no' => null,
            'attach' => null,
            'time_end' => null,
        ];
        if($diffData = array_diff_key($notifyData, $defaultData)){
            $restNotifyKey = array_keys($diffData);
            $extraData = [];
            foreach($diffData as $k => $v){
                if(strpos($k, 'coupon_type') !== false){
                    $extraData['coupon_type_n'][] = $v;
                }elseif(strpos($k, 'coupon_id') !== false){
                    $extraData['coupon_id_n'][] = $v;
                }elseif(strpos($k, 'coupon_fee') !== false){
                    $extraData['coupon_fee_n'][] = $v;
                }
            }
            if(!empty($extraData)){
                foreach($extraData as $k => $v){
                    $extraData[$k] = serialize($v);
                }
            }
            $logData = array_merge($defaultData, $notifyData, $extraData);
            foreach($restNotifyKey as $key){
                unset($logData[$key]);
            }
        }else{
            $logData = array_merge($defaultData, $notifyData);
        }
        if(array_key_exists('attach', $logData) && is_array($logData['attach'])){
            $logData['attach'] = serialize($logData['attach']);
        }
        return $logData;
    }

    public function init(){
        if(is_array($this->config)){
            $this->wechatConfig = array_merge($this->wechatConfig, $this->config);
        }
        switch($this->wechatConfig['user_type']){
            case RechargeApply::USER_TYPE_CUSTOMER:
                if($openid = Yii::$app->session->get('__wechat_public_openid', false)){
                    $this->wechatConfig['open_id'] = $openid;
                }else{
                    $this->wechatConfig['open_id'] = '';
                }
                break;

            case RechargeApply::USER_TYPE_SUPPLIER:
                $this->wechatConfig['open_id'] = '';
                break;

            case RechargeApply::USER_TYPE_ADMINISTRATOR:
                if($openid = Yii::$app->session->get('__wechat_public_openid', false)){
                    $this->wechatConfig['open_id'] = $openid;
                }else{
                    $this->wechatConfig['open_id'] = '';
                }
                break;

            default:
                $this->wechatConfig['open_id'] = '';
                break;
        }
        $this->payUrl = $this->wechatConfig['pay_url'];
        unset($this->wechatConfig['pay_url']);
        unset($this->wechatConfig['user_type']);
    }

    public function generatePayUrl(array $config = null, $return = 'throw'){
        if(is_array($config)){
            $config = array_merge($this->wechatConfig, $config);
        }else{
            $config = $this->wechatConfig;
        }
        if($urlParams = $this->generateWechatOrder($config)){
            $urlParams['total_fee'] = $config['total_fee'] * 0.01;
            $urlParamCrypt = new UrlParamCrypt;
            return ($this->payUrl . '?q=' . $urlParamCrypt->encrypt($urlParams));
        }else{
            return Yii::$app->EC->callback($return, 'creating url failed');
        }
    }

    protected function generateWechatOrder(array $config){
        $unifiedOrder = new WxPayUnifiedOrder;
        $unifiedOrder->setOutTradeNo($config['out_trade_no']);
        $unifiedOrder->setBody($config['body']);
        $unifiedOrder->setTotalFee($config['total_fee']);
        $unifiedOrder->setTradeType($config['trade_type']);
        $unifiedOrder->setOpenId($config['open_id']);
        try{
            $result = WxPayApi::unifiedOrder($unifiedOrder);
        }catch(\Exception $e){
            $result = false;
        }
        if(($returnCode = $result['return_code'] ?? false) == 'SUCCESS'){
            return $result;
        }else{
            return false;
        }
    }
}
