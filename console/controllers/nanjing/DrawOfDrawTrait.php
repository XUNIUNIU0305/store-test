<?php
namespace console\controllers\nanjing;

use Yii;
use common\models\parts\trade\recharge\nanjing\Nanjing;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicket;

trait DrawOfDrawTrait{

    public function actionDrawTicket($ticketId){
        try{
            $ticket = new DrawTicket([
                'id' => $ticketId,
            ]);
        }catch(\Exception $e){
            $this->stdout("error ticket\n");
            return 0;
        }
        $nanjing = new Nanjing;
        $drawResult = $nanjing->drawOfDraw($ticket, false);
        if($drawResult){
            $this->stdout('draw result:' . $drawResult->RespMsg . "\n");
            return 0;
        }else{
            $this->stdout("draw error\n");
            return 0;
        }
    }

    public function actionReclaimFund($ticketId){
        try{
            $ticket = new DrawTicket([
                'id' => $ticketId,
            ]);
        }catch(\Exception $e){
            $this->stdout("unavailable ticket id\n");
            return 0;
        }
        if($ticket->status != DrawTicket::STATUS_FAILURE){
            $this->stdout("ticket status incorrect\n");
            return 0;
        }
        $nanjing = new Nanjing;
        try{
            $result = $nanjing->refundDraw($ticket);
        }catch(\Exception $e){
            $this->stdout($e->getMessage() . "\n");
            return 0;
        }
        $this->stdout("响应码： {$result->RespCode}\n响应信息： {$result->RespMsg}\n交易流水： {$result->TransSeqNo}\n");
        return 0;
    }
}
