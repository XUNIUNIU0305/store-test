<?php
namespace common\models\parts\trade\recharge\nanjing\message;

use Yii;
use common\ActiveRecord\NanjingPayBalanceAR;
use common\models\parts\trade\recharge\nanjing\Nanjing;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicket;

class PayOfDraw extends BaseAbstract{

    public $drawTicketId;
    public $merchantDateTime;
    public $merchantSeqNo;
    public $feeAmount = 0;
    public $orderNo;
    public $productInfo;
    public $respCode;
    public $respMsg;
    public $transSeqNo;

    protected function runExtra() : bool{
        $drawTicket = new DrawTicket(['id' => $this->drawTicketId]);
        $status = $this->respCode == '000000' ? 1 : 2;
        $insertValues = [
            'operation_type' => Nanjing::OPERATION_PAY_OF_DRAW,
            'corresponding_id' => $this->drawTicketId,
            'payer_nanjing_account_id' => 0,
            'receiver_nanjing_account_id' => $drawTicket->nanjingAccount->id,
            'receiver_type' => $drawTicket->userAccount->userType,
            'receiver_account' => $drawTicket->userAccount->userAccount,
            'merchant_date_time' => $this->merchantDateTime,
            'merchant_seq_no' => $this->merchantSeqNo,
            'fee_amount' => $this->feeAmount,
            'trans_amount' => $drawTicket->rmb,
            'order_no' => $this->merchantSeqNo,
            'product_info' => $this->productInfo,
            'status' => $status,
            'resp_code' => $this->respCode,
            'resp_msg' => $this->respMsg,
            'trans_seq_no' => $this->transSeqNo,
        ];
        $payId = Yii::$app->RQ->AR(new NanjingPayBalanceAR)->insert($insertValues);
        $drawTicket->lock = false;
        $drawTicket->nanjingPayBalanceId = $payId;
        if($status == 1){
            $drawTicket->status = DrawTicket::STATUS_PASS;
            $nanjing = new Nanjing;
            $nanjing->drawOfDraw($drawTicket);
        }else{
            $drawTicket->handleErrMsg = $this->respMsg;
        }
        return true;
    }
}
