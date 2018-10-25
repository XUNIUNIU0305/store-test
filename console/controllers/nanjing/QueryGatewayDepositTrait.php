<?php
namespace console\controllers\nanjing;

use Yii;
use common\ActiveRecord\RechargeApplyAR;
use common\ActiveRecord\NanjingGatewayApplyAR;
use common\ActiveRecord\NanjingGatewayDepositAR;
use common\models\parts\trade\recharge\nanjing\Nanjing;
use console\models\nanjing\GatewayPayment;

trait QueryGatewayDepositTrait{

    public function actionQueryDepositStatus(){
        $dateTime = new \DateTime;
        $dateTime->modify('5 days ago');
        $gateway = Yii::$app->RQ->AR(new NanjingGatewayDepositAR)->all([
            'select' => ['id', 'nanjing_gateway_apply_id'],
            'where' => ['status' => 1],
            'andWhere' => ['<', 'end_datetime', $dateTime->format('Y-m-d H:i:s')],
        ]);
        if(!$gateway)return 0;
        $nanjing = new Nanjing;
        try{
            foreach($gateway as $apply){
                $merchantSeqNo = NanjingGatewayApplyAR::findOne($apply['nanjing_gateway_apply_id'])->merchant_seq_no;
                $queryResult = $nanjing->queryGatewayDeposit($merchantSeqNo);
                $gatewayDeposit = NanjingGatewayDepositAR::findOne($apply['id']);
                if($queryResult->isSuccess()){
                    if($queryResult->List[0]['TransStep'] == '5'){
                        $status = 4;
                    }else{
                        $status = 5;
                    }
                    Yii::$app->RQ->AR($gatewayDeposit)->update([
                        'status' => $status,
                        'deposit_datetime' => date('Y-m-d H:i:s'),
                    ]);
                }else{
                    Yii::$app->RQ->AR($gatewayDeposit)->update([
                        'query_error_msg' => $queryResult->RespMsg,
                    ]);
                }
            }
        }catch(\Exception $e){
            Yii::error($e, __METHOD__);
        }
        return 0;
    }

    public function actionDepositConfirm($updateResult = true, $depositId = null){
        if(is_null($depositId)){
            $applyIds = NanjingGatewayDepositAR::find()->select(['nanjing_gateway_apply_id'])->where(['status' => 3])->column();
        }else{
            $applyIds = (array)NanjingGatewayDepositAR::findOne($depositId)->nanjing_gateway_apply_id;
        }
        $nanjing = new Nanjing;
        foreach($applyIds as $applyId){
            $merchantSeqNo = NanjingGatewayApplyAR::findOne($applyId)->merchant_seq_no;
            $queryResult = $nanjing->queryGatewayDeposit($merchantSeqNo);
            if($queryResult->isSuccess()){
                if(count($queryResult->List) == 1){
                    $transStatus = $queryResult->List[0]['TransStatus'];
                    $transStep = $queryResult->List[0]['TransStep'];
                    if($depositId){
                        $this->stdout("交易流水： {$queryResult->List[0]['MerchantSeqNo']}\n交易状态： {$queryResult->List[0]['TransStatus']}\n交易金额： {$queryResult->List[0]['TransAmount']}\n交易日期： {$queryResult->List[0]['TransDate']}\n交易时间： {$queryResult->List[0]['TransTime']}\n交易步骤： {$queryResult->List[0]['TransStep']}\n");
                    }
                    if($updateResult && $transStatus == '01'){
                        $AR = NanjingGatewayDepositAR::findOne([
                            'nanjing_gateway_apply_id' => $applyId,
                        ]);
                        if($AR->status == 3){
                            Yii::$app->RQ->AR($AR)->update([
                                'status' => 2,
                                'end_datetime' => date('Y-m-d H:i:s'),
                            ]);
                            $this->stdout("Apply ID: {$applyId} update result completed\n");
                        }
                    }
                }else{
                    $this->stdout("Apply ID: {$applyId} has more than 1 list");
                }
            }else{
                $this->stdout("Apply ID: {$applyId} query failed\n");
            }
        }
        return 0;
    }

    public function actionRehandle($rechargeNumber){
        $rechargeApplyId = Yii::$app->RQ->AR(new RechargeApplyAR)->scalar([
            'select' => ['id'],
            'where' => ['recharge_number' => $rechargeNumber],
        ]);
        if(!$rechargeApplyId){
            $this->stdout("unavailable recharge number\n");
            return 0;
        }
        $gatewayApply = Yii::$app->RQ->AR(new NanjingGatewayApplyAR)->one([
            'where' => ['merchant_seq_no' => $rechargeNumber],
        ]);
        if(!$gatewayApply){
            $this->stdout("unable to find gateway apply data, check payment method\n");
            return 0;
        }
        $gatewayQuery = Yii::$app->RQ->AR(new NanjingGatewayDepositAR)->one([
            'where' => [
                'nanjing_gateway_apply_id' => $gatewayApply['id'],
                'recharge_apply_id' => $rechargeApplyId,
            ],
        ]);
        if(!$gatewayQuery){
            $this->stdout("unhandled data\n");
            return 0;
        }
        if($gatewayQuery['status'] != 2){
            $this->stdout("unable to rehandle this data\n");
            return 0;
        }
        $plain = $this->analyzePlain($gatewayApply['plain']);
        $gatewayPayment = new GatewayPayment([
            'merchantSeqNo' => $rechargeNumber,
            'rechargeApplyId' => $rechargeApplyId,
            'gatewayApplyId' => $gatewayApply['id'],
            'gatewayQueryId' => $gatewayQuery['id'],
            'callbackPlain' => $plain['Plain'],
            'originalPlain' => $plain['OriginalPlain'],
        ]);
        try{
            $callback = $gatewayPayment->runManual();
            $this->stdout("操作成功\n\n");
        }catch(\Exception $e){
            $this->stdout($e->getMessage() . "\n");
            return 0;
        }
        $this->stdout("交易流水： {$callback->List[0]['MerchantSeqNo']}\n交易状态： {$callback->List[0]['TransStatus']}\n交易金额： {$callback->List[0]['TransAmount']}\n交易日期： {$callback->List[0]['TransDate']}\n交易时间： {$callback->List[0]['TransTime']}\n交易步骤： {$callback->List[0]['TransStep']}\n");
        return 0;
    }

    protected function analyzePlain(string $plain){
        $originalPlain = $plain;
        $keyAndValue = explode('|', $plain);
        $plain = [];
        foreach($keyAndValue as $oneParam){
            list($paramName, $paramValue) = explode('=', $oneParam);
            $plain[$paramName] = $paramValue;
        }
        return [
            'OriginalPlain' => $originalPlain,
            'Plain' => $plain,
        ];
    }
}
