<?php
namespace common\models\parts\trade\recharge\abc\message;

use Yii;
use common\components\amqp;
use common\components\amqp\Message;
use common\components\amqp\AmqpTaskAbstract;
use common\models\parts\trade\recharge\RechargeApply;
use api\components\handler\RechargeHandler;
use custom\models\parts\trade\Wallet;
use custom\models\parts\trade\Trade;

class Notify extends AmqpTaskAbstract{

    public $notifyMsg;
    public $retryTime = 0;

    public function init(){
        Yii::$app->db->queryMaster = true;
    }

    public function run(){
        if($this->handleNotify()){
            return true;
        }else{
            if($this->retryTime < 100){
                sleep(1);
                ++$this->retryTime;
                Yii::$app->amqp->publish(new Message($this));
            }else{
                Yii::error($this->notifyMsg, __METHOD__);
            }
            return false;
        }
    }

    protected function handleNotify(){
        if(strpos($this->notifyMsg, 'PaySuccess') === false){
            return true;
        }else{
            preg_match('/<ExtOrderID>(.*?)<\/ExtOrderID>/', $this->notifyMsg, $extOrderIds);
            $rechargeNumber = $extOrderIds[1] ?? false;
            preg_match('/<EbizBillNO>(.*?)<\/EbizBillNO>/', $this->notifyMsg, $ebizBillNos);
            $ebizBillNo = $ebizBillNos[1] ?? false;
        }
        if(!$rechargeNumber || !$ebizBillNo)return true;
        $rechargeApply = new RechargeApply(['rechargeNumber' => $rechargeNumber]);
        if(!$rechargeApply->isWait)return true;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $client = new \common\models\parts\trade\recharge\abc\Abc;
            $abchinaNotifyId = $client->writeNotifyLog($this->notifyMsg);
            if(!RechargeHandler::recharge($rechargeApply, $abchinaNotifyId))throw new \Exception;
            $transaction->commit();
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
        if($rechargeApply->userType == $rechargeApply::USER_TYPE_CUSTOMER){
            if($rechargeApply->detail->tradeId){
                $transaction = Yii::$app->db->beginTransaction();
                try{
                    $wallet = new Wallet([
                        'userId' => $rechargeApply->detail->userId,
                    ]);
                    $trade = new Trade([
                        'id' => $rechargeApply->detail->tradeId,
                    ]);
                    $wallet->pay($trade);
                    $transaction->commit();
                }catch(\Exception $e){
                    $transaction->rollBack();
                }
            }
        }
        return true;
    }
}
