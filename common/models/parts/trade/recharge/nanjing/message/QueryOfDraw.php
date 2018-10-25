<?php
namespace common\models\parts\trade\recharge\nanjing\message;

use Yii;
use common\components\amqp\Message;
use common\models\parts\trade\recharge\nanjing\Nanjing;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicket;
use common\models\parts\trade\recharge\nanjing\data\NanjingCallback;

class QueryOfDraw extends BaseAbstract{

    public $callbackPlain;
    public $drawTicketId;
    public $merchantSeqNo;
    public $queryTime = 0;

    public function runExtra() : bool{
        $nanjing = new Nanjing;
        $result = $nanjing->queryOfDraw($this->merchantSeqNo, false);
        if($result instanceof NanjingCallback){
            if($result->RespCode == '000000'){
                $drawTicket = new DrawTicket(['id' => $this->drawTicketId]);
                $drawTicket->handleErrMsg = $result->RespMsg;
                if($result->List[0]['TransStatus'] == '00'){ //成功
                    $success = new \common\models\parts\trade\recharge\nanjing\message\SuccessOfDraw([
                        'callback' => $result,
                        'drawTicketId' => $this->drawTicketId,
                    ]);
                    Yii::$app->amqp->publish(new Message($success));
                }elseif($result->List[0]['TransStatus'] == '07'){ //处理中
                    $this->queryAgain(false);
                }else{ //其他，失败
                    $failure = new \common\models\parts\trade\recharge\nanjing\message\FailureOfDraw([
                        'callback' => $result,
                        'drawTicketId' => $this->drawTicketId,
                    ]);
                    Yii::$app->amqp->publish(new Message($failure));
                }
            }else{
                $this->queryAgain(true);
            }
        }else{
            $this->queryAgain(true);
        }
        return true;
    }

    protected function queryAgain(bool $addTime){
        if($addTime)$this->queryTime++;
        $this->sleepTime = 1;
        sleep(10);
        if($this->queryTime < 100){
            Yii::$app->amqp->publish(new Message($this));
        }else{
            Yii::error([
                'callback' => $this->callbackPlain,
                'drawTicketId' => $this->drawTicketId,
            ]);
        }
    }
}
