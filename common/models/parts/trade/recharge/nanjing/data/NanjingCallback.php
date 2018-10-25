<?php
namespace common\models\parts\trade\recharge\nanjing\data;

use Yii;
use common\components\amqp\Message;
use yii\base\InvalidConfigException;
use common\models\parts\trade\recharge\nanjing\message\ExecuteLog;

final class NanjingCallback{

    private $_paygateCert; //银行支付平台证书文件路径

    private $_params;
    private $_executeLog;

    public function __construct(ExecuteLog $executeLog, string $xml, string $cert){
        $this->_executeLog = $executeLog;
        $this->_paygateCert = $cert;
        if(!$array = $this->generateArray($xml))throw new InvalidConfigException('unavailable data');
        if(!$this->verifySign($array['Plain'], $array['Signature']))throw new InvalidConfigException('error callback data');
        $array['OriginalPlain'] = $array['Plain'];
        $array['Plain'] = $this->initPlain($array['Plain']);
        $this->_params = $array;
        $this->recordExecute();
    }

    public function __set($name, $value){
        return;
    }

    public function __get($name){
        return ($this->_params[$name] ?? ($this->_params['Plain'][$name] ?? null));
    }

    public function isSuccess(){
        return ($this->RespCode == '000000');
    }

    private function recordExecute(){
        if($this->_executeLog instanceof ExecuteLog){
            $logData = [
                'callback' => $this,
                'responseCode' => $this->RespCode ?? '',
                'responseMsg' => $this->RespMsg ?? '',
                'transSeqNo' => $this->TransSeqNo ?? '',
                'responseSerializedData' => serialize($this->_params),
                'responseDatetime' => date('Y-m-d H:i:s', $time = time()),
                'responseUnixtime' => $time,
            ];
            foreach($logData as $paramName => $value){
                $this->_executeLog->{$paramName} = $value;
            }
            $this->_executeLog->init();
            $amqpMessage = new Message($this->_executeLog);
            Yii::$app->amqp->publish($amqpMessage);
        }
    }

    private function initPlain(string $string){
        $plainString = rtrim($string, '|^');
        if(strpos($string, '^|') === false){
            return $this->convertStringToArray($plainString);
        }else{
            $array = explode('^|', $plainString);
            $plain = $this->convertStringToArray($array[0]);
            $plain['List'] = [];
            unset($array[0]);
            foreach($array as $string){
                $plain['List'][] = $this->convertStringToArray($string);
            }
            return $plain;
        }
    }

    private function convertStringToArray(string $string){
        $array = explode('|', $string);
        $result = [];
        foreach($array as $param){
            list($paramName, $paramValue) = explode('=', $param);
            $result[$paramName] = $paramValue;
        }
        return $result;
    }

    private function generateArray(string $xml){
        libxml_disable_entity_loader(true);
        $array = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        if(isset($array['transName']) && isset($array['Plain']) && isset($array['Signature'])){
            return $array;
        }else{
            return false;
        }
    }

    /**
     * 验证签名
     *
     * @return boolean
     */
    public function verifySign(string $plain, string $signature){
        $cert = file_get_contents($this->_paygateCert);
        if(strpos($cert, '-----BEGIN CERTIFICATE-----') === false){
            $cert = "-----BEGIN CERTIFICATE-----\n" . chunk_split(base64_encode($cert), 64, "\n") . "-----END CERTIFICATE-----\n";
        }
        $paygateCertData = openssl_x509_read($cert);
        $pkeyid = openssl_pkey_get_public($paygateCertData);
        $result = openssl_verify($plain, hex2bin($signature), $pkeyid, OPENSSL_ALGO_MD5);
        openssl_free_key($pkeyid);
        return $result == 1;
    }
}
