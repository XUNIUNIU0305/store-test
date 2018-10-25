<?php
namespace common\models\parts\trade\recharge\nanjing\message;

use Yii;
use common\ActiveRecord\NanjingDrawAR;
use common\models\parts\trade\recharge\nanjing\Nanjing;

class DirectDraw extends BaseAbstract{

    public $callbackPlain;
    public $nanjingAccountId;
    public $userType;
    public $userAccount;
    public $merchantSeqNo;
    public $merchantDateTime;
    public $transAmount;

    protected function runExtra() : bool{
        Yii::$app->RQ->AR(new NanjingDrawAR)->insert([
            'operation_type' => Nanjing::OPERATION_DIRECT_DRAW,
            'corresponding_id' => 0,
            'nanjing_account_id' => $this->nanjingAccountId,
            'user_type' => $this->userType,
            'user_account' => $this->userAccount,
            'merchant_seq_no' => $this->merchantSeqNo,
            'merchant_date_time' => $this->merchantDateTime,
            'trans_amount' => $this->transAmount,
            'status' => $this->callbackPlain['RespCode'] == '000000' ? 1 : 2,
            'resp_code' => $this->callbackPlain['RespCode'],
            'resp_msg' => $this->callbackPlain['RespMsg'],
            'trans_seq_no' => $this->callbackPlain['TransSeqNo'],
        ]);
        return true;
    }
}
