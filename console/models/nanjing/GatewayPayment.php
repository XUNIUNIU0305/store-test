<?php
namespace console\models\nanjing;

use Yii;
use common\ActiveRecord\NanjingGatewayDepositAR;
use common\models\parts\trade\recharge\nanjing\Nanjing;

class GatewayPayment extends \common\models\parts\trade\recharge\nanjing\message\GatewayPayment{

    public $merchantSeqNo;
    public $queryTimes = 0;
    public $rechargeApplyId;
    public $gatewayApplyId;
    public $gatewayQueryId;
    public $errorTimes = 0;

    public function runManual(){
        $nanjing = new Nanjing;
        $callback = $nanjing->queryGatewayDeposit($this->merchantSeqNo);
        if(!$callback->isSuccess())throw new \Exception('query failed');
        $transStatus = $callback->List[0]['TransStatus'];
        if($transStatus == '00' || $transStatus == '01'){
            $transaction = Yii::$app->db->beginTransaction();
            try{
                Yii::$app->RQ->AR(NanjingGatewayDepositAR::findOne($this->gatewayQueryId))->update([
                    'status' => ($transStatus == '00' ? 1 : 2),
                    'end_datetime' => date('Y-m-d H:i:s'),
                ]);
                if($transStatus == '00'){
                    if(!$this->handlePayment())throw new \Exception("paying trade failed");
                }else{
                    throw new \Exception("TransStatus: {$transStatus}\nunable to pay");
                }
                $transaction->commit();
            }catch(\Exception $e){
                $transaction->rollBack();
                throw $e;
            }
        }
        return $callback;
    }

    public function runExtra() : bool{
        return true;
    }

    protected function getSleepTime(){
        return 0;
    }

    protected function fillData(){
        return true;
    }
}
