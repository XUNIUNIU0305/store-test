<?php
namespace common\models\parts\trade\recharge\nanjing;

use Yii;
use yii\base\Object;
use common\models\parts\trade\recharge\nanjing\message\GatewayPayment;
use common\components\amqp\Message;
use common\ActiveRecord\NanjingGatewayApplyAR;

class NanjingGatewayPayment extends Object{

    public $config;

    private $_merchantSeqNo;
    private $_acctType;
    private $_transAmount;
    private $_userId;
    private $_userType;
    private $_prodHost = 'https://ebank.njcb.com.cn/paygate/main';

    public function init(){
        if(is_array($this->config)){
            $this->_merchantSeqNo = $this->config['merchantSeqNo'] ?? null;
            $this->_acctType = $this->config['acctType'] ?? null;
            $this->_transAmount = $this->config['transAmount'] ?? null;
            $this->_userId = $this->config['userId'] ?? null;
            $this->_userType = $this->config['userType'] ?? null;
        }
    }

    public function generatePayUrl($config){
        if(is_array($config)){
            $this->config = array_merge($this->config, $config);
            $this->init();
        }
        if(!$this->_merchantSeqNo)throw new \Exception;
        $nanjing = new Nanjing;
        $callback = $nanjing->gatewayDeposit($this->_merchantSeqNo, $this->_acctType, $this->_transAmount);
        if($callback->RespCode != 'S00025' || !$callback->transName || !$callback->OriginalPlain || !$callback->Signature)throw new \Exception;
        $host = $callback->RedirectUrl ? : $this->_prodHost;
        $transName = $callback->transName;
        $plain = urlencode($callback->OriginalPlain);
        $signature = $callback->Signature;
        $insertId = Yii::$app->RQ->AR(new NanjingGatewayApplyAR)->insert([
            'operation_type' => Nanjing::OPERATION_GATEWAY_APPLY,
            'user_id' => $this->_userId,
            'user_type' => $this->_userType,
            'merchant_seq_no' => $this->_merchantSeqNo,
            'oper_type' => '300',
            'acct_type' => $this->_acctType,
            'trans_amount' => $this->_transAmount,
            'apply_datetime' => date('Y-m-d H:i:s'),
            'apply_unixtime' => time(),
            'resp_code' => $callback->RespCode,
            'resp_msg' => $callback->RespMsg,
            'trans_name' => $transName,
            'redirect_url' => $host,
            'plain' => $callback->OriginalPlain,
        ], false);
        if(!$insertId)throw new \Exception;
        $gatewayPaymentMessage = new GatewayPayment([
            'callback' => $callback,
            'merchantSeqNo' => $this->_merchantSeqNo,
        ]);
        Yii::$app->amqp->publish(new Message($gatewayPaymentMessage));
        return "{$host}?transName={$transName}&Plain={$plain}&Signature={$signature}";
    }
}
