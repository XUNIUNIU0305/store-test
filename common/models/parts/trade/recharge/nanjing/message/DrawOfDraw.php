<?php
namespace common\models\parts\trade\recharge\nanjing\message;

use Yii;
use common\components\amqp\Message;
use common\ActiveRecord\NanjingDrawAR;
use common\models\parts\trade\recharge\nanjing\Nanjing;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicket;

class DrawOfDraw extends BaseAbstract{

    public $callbackPlain;
    public $drawTicketId;
    public $merchantSeqNo;
    public $merchantDateTime;

    public function runExtra() : bool{
        $drawTicket = new DrawTicket(['id' => $this->drawTicketId]);
        $respCode = $this->callbackPlain['RespCode'];
        $status = $respCode == '000000' ? 1 : 2;
        $insertValues = [
            'operation_type' => Nanjing::OPERATION_DRAW_OF_DRAW,
            'corresponding_id' => $this->drawTicketId,
            'nanjing_account_id' => $drawTicket->nanjingAccount->id,
            'user_type' => $drawTicket->userAccount->userType,
            'user_account' => $drawTicket->userAccount->userAccount,
            'trans_amount' => $drawTicket->rmb,
            'merchant_date_time' => $this->merchantDateTime,
            'merchant_seq_no' => $this->merchantSeqNo,
            'status' => $status,
            'resp_code' => $respCode,
            'resp_msg' => $this->callbackPlain['RespMsg'],
            'trans_seq_no' => $this->callbackPlain['TransSeqNo'],
        ];
        $logId = Yii::$app->RQ->AR(new NanjingDrawAR)->insert($insertValues);
        $drawTicket->nanjingDrawId = $logId;
        $drawTicket->handleErrMsg = $this->callbackPlain['RespMsg'];
        $queryOfDraw = new \common\models\parts\trade\recharge\nanjing\message\QueryOfDraw([
            'callbackPlain' => 'no data',
            'originalPlain' => 'no data',
            'drawTicketId' => $this->drawTicketId,
            'merchantSeqNo' => $this->merchantSeqNo,
        ]);
        Yii::$app->amqp->publish(new Message($queryOfDraw));
        return true;
    }
}
