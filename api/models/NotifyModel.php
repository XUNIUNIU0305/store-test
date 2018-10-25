<?php
namespace api\models;

use Yii;
use common\models\Model;
use common\models\parts\trade\recharge\alipay\Alipay;
use api\components\handler\RechargeHandler;
use common\models\parts\trade\recharge\RechargeApply;
use custom\models\parts\trade\Wallet;
use custom\models\parts\trade\Trade;
use common\models\parts\trade\recharge\wechat\Wechat;

class NotifyModel extends Model{

    const SCE_ALIPAY_HANDLE = 'alipay_handle';
    const SCE_WXPAY_HANDLE = 'wxpay_handle';
    const SCE_ABCHINA_HANDLE = 'abchina_handle';

    public function scenarios(){
        return [
            self::SCE_ALIPAY_HANDLE => [],
            self::SCE_WXPAY_HANDLE => [],
            self::SCE_ABCHINA_HANDLE => [],
        ];
    }

    public function alipayHandle(){
        $alipay = new Alipay();
        if(!$alipay->verifyNotify())return false;
        //$_POST = [
            //'trade_status' => 'TRADE_SUCCESS',
            //'out_trade_no' => 35,
            //'notify_time' => Yii::$app->time->fullDate,
        //];
        if($_POST['trade_status'] == 'TRADE_SUCCESS'){
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $rechargeApply = new RechargeApply(['rechargeNumber' => $_POST['out_trade_no']]);
                if(!$rechargeApply->isWait)return true;
                if(!$alipayNotifyId = $alipay->writeLog())throw new \Exception;
                if(!RechargeHandler::recharge($rechargeApply, $alipayNotifyId))throw new \Exception;
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
        }elseif($_POST['trade_status'] == 'TRADE_FINISHED'){
            return true;
        }else{
            return false;
        }
    }

    public function wxpayHandle(){
        $wechat = new Wechat;
        if(!$wechat->verifyNotify())return false;
        $notifyData = $wechat->getNotifyData();
        if($notifyData['result_code'] == 'SUCCESS'){
            $rechargeApply = new RechargeApply(['rechargeNumber' => $notifyData['out_trade_no']]);
            if(!$rechargeApply->isWait)return true;
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $wxpayNotifyId = $wechat->writeLog();
                if(!RechargeHandler::recharge($rechargeApply, $wxpayNotifyId))throw new \Exception('recharging failed');
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
            if($rechargeApply->userType == $rechargeApply::USER_TYPE_ADMINISTRATOR){
                if($tradeId = $rechargeApply->detail->tradeId){
                    $transaction = Yii::$app->db->beginTransaction();
                    try{
                        $wallet = new \admin\models\parts\trade\Wallet;
                        $trade = new \admin\models\parts\trade\Trade(['id' => $tradeId]);
                        $wallet->pay($trade, $wallet);
                        $transaction->commit();
                    }catch(\Exception $e){
                        $transaction->rollBack();
                    }
                }
            }
            return true;
        }else{
            return true;
        }
    }

    public function abchinaHandle(){
        $client = new \common\models\parts\trade\recharge\abc\Abc;
        if(!$notify = $_POST['MSG'] ?? false)return false;
        if(!$notifyMsg = $client->decodeNotify($notify, 'AES', 'UTF-8'))return false;
        if(strpos($notifyMsg, 'PaySuccess') === false){
            return false;
        }else{
            preg_match('/<ExtOrderID>(.*?)<\/ExtOrderID>/', $notifyMsg, $extOrderIds);
            $rechargeNumber = $extOrderIds[1] ?? false;
            preg_match('/<EbizBillNO>(.*?)<\/EbizBillNO>/', $notifyMsg, $ebizBillNos);
            $ebizBillNo = $ebizBillNos[1] ?? false;
        }
        if(!$rechargeNumber || !$ebizBillNo)return false;
        $rechargeApply = new RechargeApply(['rechargeNumber' => $rechargeNumber]);
        if(!$rechargeApply->isWait)return true;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $client = new \common\models\parts\trade\recharge\abc\Abc;
            $abchinaNotifyId = $client->writeNotifyLog($notifyMsg);
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
        //Yii::$app->amqp->publish(new \common\components\amqp\Message(new \common\models\parts\trade\recharge\abc\message\Notify([
            //'notifyMsg' => $result,
        //])));
        return true;
    }
}
