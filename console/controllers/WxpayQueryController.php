<?php
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;
use api\components\handler\RechargeHandler;
use common\models\parts\trade\recharge\RechargeApply;
use custom\models\parts\trade\Wallet;
use custom\models\parts\trade\Trade;
use common\models\parts\trade\recharge\wechat\Wechat;

class WxpayQueryController extends Controller{

    public function actionOutTradeNo($no){
        $query = new \common\models\parts\trade\recharge\wechat\data\WxPayOrderQuery();
        $query->setOutTradeNo($no);
        $queryResult = \common\models\parts\trade\recharge\wechat\WxPayApi::orderQuery($query);
        $wechat = new Wechat;
        $wechat->setNotifyData($queryResult);
        if(!$wechat->verifyNotify()){
            $this->stdout("回调信息验证未通过\n");
            return 0;
        }
        $notifyData = $wechat->getNotifyData();
        var_dump($notifyData);
        if($notifyData['result_code'] == 'SUCCESS'){
            $rechargeApply = new RechargeApply(['rechargeNumber' => $notifyData['out_trade_no']]);
            if(!$rechargeApply->isWait){
                $this->stdout("支付已处理\n");
                return 0;
            }
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $wxpayNotifyId = $wechat->writeLog();
                if(!RechargeHandler::recharge($rechargeApply, $wxpayNotifyId))throw new \Exception('recharging failed');
                $transaction->commit();
                $this->stdout("充值处理成功\n");
            }catch(\Exception $e){
                $transaction->rollBack();
                $this->stdout("充值处理失败\n");
                return 0;
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
                        if($wallet->pay($trade)){
                            $this->stdout("支付处理成功\n");
                        }else{
                            $this->stdout("支付处理失败\n");
                        }
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
                        if($wallet->pay($trade, $wallet)){
                            $this->stdout("支付处理成功\n");
                        }else{
                            $this->stdout("支付处理失败\n");
                        }
                        $transaction->commit();
                    }catch(\Exception $e){
                        $transaction->rollBack();
                    }
                }
            }
            $this->stdout("处理完成\n");
            return 0;
        }else{
            $this->stdout("支付未成功\n");
            return 0;
        }
    }
}
