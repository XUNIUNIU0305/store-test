<?php
namespace common\models\parts\trade\recharge\nanjing\message;

use Yii;
use common\components\amqp\Message;
use common\ActiveRecord\RechargeApplyAR;
use common\ActiveRecord\NanjingGatewayApplyAR;
use common\ActiveRecord\NanjingGatewayDepositAR;
use common\models\parts\trade\recharge\nanjing\Nanjing;
use common\models\parts\trade\recharge\RechargeApply;
use custom\models\parts\trade\Wallet;
use custom\models\parts\trade\Trade;
use api\components\handler\RechargeHandler;

class GatewayPayment extends BaseAbstract{

    public $merchantSeqNo;
    public $queryTimes = 0;
    public $rechargeApplyId;
    public $gatewayApplyId;
    public $gatewayQueryId;
    public $errorTimes = 0;

    public function runExtra() : bool{
        $this->sleepTime = -1;
        sleep($this->getSleepTime());
        if(is_null($this->rechargeApplyId)){
            if($this->fillData() == false){
                if($this->errorTimes < 100){
                    Yii::$app->amqp->publish(new Message($this));
                }
                return true;
            }
        };
        $nanjing = new Nanjing;
        try{
            $callback = $nanjing->queryGatewayDeposit($this->merchantSeqNo);
            if(!$callback->isSuccess()){
                Yii::$app->RQ->AR(NanjingGatewayDepositAR::findOne($this->gatewayQueryId))->update([
                    'query_error_msg' => $callback->RespMsg,
                ]);
                throw new \Exception;
            }
            $transStatus = $callback->List[0]['TransStatus'];
            ++$this->queryTimes;
        }catch(\Exception $e){
            ++$this->errorTimes;
            if($this->errorTimes < 10000){
                Yii::$app->amqp->publish(new Message($this));
            }else{
                Yii::$app->RQ->AR(NanjingGatewayDepositAR::findOne($this->gatewayQueryId))->update([
                    'status' => 2,
                    'end_datetime' => date('Y-m-d H:i:s'),
                ]);
            }
            return true;
        }
        if($transStatus == '00' || $transStatus == '01'){
            Yii::$app->RQ->AR(NanjingGatewayDepositAR::findOne($this->gatewayQueryId))->update([
                'status' => ($transStatus == '00' ? 1 : 2),
                'end_datetime' => date('Y-m-d H:i:s'),
            ]);
            if($transStatus == '00'){
                $this->handlePayment();
            }
        }else{
            Yii::$app->amqp->publish(new Message($this));
        }
        return true;
    }

    protected function handlePayment(){
        $rechargeApply = new RechargeApply(['rechargeNumber' => $this->merchantSeqNo]);
        if(!$rechargeApply->isWait)return true;
        if(!RechargeHandler::recharge($rechargeApply, $this->gatewayQueryId))throw new \Exception;
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
                    return false;
                }
            }
        }
        return true;
    }

    protected function getSleepTime(){
        if($this->queryTimes <= 60){//3min
            $sleepTime = 3;
        }elseif($this->queryTimes <= 102){//10min
            $sleepTime = 10;
        }elseif($this->queryTimes <= 142){//30min
            $sleepTime = 30;
        }else{//to the end
            $sleepTime = 60;
        }
        return $sleepTime;
    }

    protected function fillData(){
        $rechargeApplyId = Yii::$app->RQ->AR(new RechargeApplyAR)->scalar([
            'select' => ['id'],
            'where' => ['recharge_number' => $this->merchantSeqNo],
        ]);
        if($rechargeApplyId){
            $this->rechargeApplyId = $rechargeApplyId;
            $this->gatewayApplyId = Yii::$app->RQ->AR(new NanjingGatewayApplyAR)->scalar([
                'select' => ['id'],
                'where' => ['merchant_seq_no' => $this->merchantSeqNo],
            ]);
            $this->gatewayQueryId = Yii::$app->RQ->AR(new NanjingGatewayDepositAR)->insert([
                'nanjing_gateway_apply_id' => $this->gatewayApplyId,
                'recharge_apply_id' => $this->rechargeApplyId,
                'status' => 3,
            ]);
            return true;
        }else{
            ++$this->errorTimes;
            return false;
        }
    }
}
