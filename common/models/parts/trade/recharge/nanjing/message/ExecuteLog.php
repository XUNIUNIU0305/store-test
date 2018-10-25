<?php
namespace common\models\parts\trade\recharge\nanjing\message;

use Yii;
use common\ActiveRecord\NanjingExecuteLogAR;

class ExecuteLog extends BaseAbstract{

    public $operationType;
    public $merchantid;
    public $meruserid;
    public $merchantSeqNo;
    public $requestData;
    public $responseCode;
    public $responseMsg;
    public $transSeqNo;
    public $responseOriginalData;
    public $responseSerializedData;
    public $requestDatetime;
    public $requestUnixtime;
    public $responseDatetime;
    public $responseUnixtime;

    protected $logDatetime;
    protected $logUnixtime;

    public function init(){
        parent::init();
        $this->logDatetime = date('Y-m-d H:i:s');
        $this->logUnixtime = time();
    }

    protected function runExtra() : bool{
        Yii::$app->RQ->AR(new NanjingExecuteLogAR)->insert([
            'operation_type' => $this->operationType,
            'merchantid' => $this->merchantid,
            'meruserid' => $this->meruserid,
            'merchant_seq_no' => $this->merchantSeqNo,
            'request_data' => $this->requestData,
            'response_code' => $this->responseCode,
            'response_msg' => $this->responseMsg,
            'trans_seq_no' => $this->transSeqNo,
            'response_original_data' => $this->responseOriginalData,
            'response_serialized_data' => $this->responseSerializedData,
            'request_datetime' => $this->requestDatetime,
            'request_unixtime' => $this->requestUnixtime,
            'response_datetime' => $this->responseDatetime,
            'response_unixtime' => $this->responseUnixtime,
            'log_datetime' => $this->logDatetime,
            'log_unixtime' => $this->logUnixtime,
        ]);
        return true;
    }
}
