<?php
namespace console\controllers\nanjing;

use Yii;
use console\models\sms\Sms;
use common\models\parts\sms\SmsSender;
use common\ActiveRecord\BusinessUserWalletAR;
use common\models\parts\trade\recharge\nanjing\Nanjing;
use common\models\parts\trade\recharge\nanjing\account\MainAccount;

trait BalanceWarningTrait{

    public function actionBalanceWarning(){
        if(($deadline = $this->_getDeadLine()) && ($list = $this->_getNotifyList())){
            $nanjing = new Nanjing;
            if(($bankBalanceCallback = $nanjing->queryBalance($nanjing->mainAccount, false)) && $bankBalanceCallback->isSuccess()){
                $bankBalance = $bankBalanceCallback->List[0]['Amount'];
                if($bankBalance < $deadline){
                    return ($this->sendWarningMessage($bankBalance, $list) ? 0 : 1);
                }
            }
        }
        return 0;
    }

    private function sendWarningMessage($bankBalance, $notifyList){
        $sms = new Sms([
            'mobile' => $notifyList,
            'signName' => '九大爷平台',
            'templateCode' => 'SMS_144456170',
            'param' => [
                'time' => date('Y-m-d H:i'),
                'bankBalance' => $bankBalance,
                'accountBalance' => $this->_getAllAccountBalance(),
            ],
        ]);
        return (new SmsSender)->send($sms, false);
    }

    private function _getAllAccountBalance(){
        return BusinessUserWalletAR::find()->sum('rmb');
    }

    /**
     * deadline.php
     * 直接返回数字
     * return integer;
     */
    private function _getDeadLine(){
        $configFile = __DIR__ . '/warning/deadline.php';
        if(is_file($configFile)){
            try{
                return abs((int)include($configFile));
            }catch(\Exception $e){
                return 0;
            }
        }else{
            return 0;
        }
    }

    /**
     * list.php
     * 返回数组
     * return [
     *     integer, //手机号码
     * ];
     */
    private function _getNotifyList(){
        $configFile = __DIR__ . '/warning/list.php';
        if(is_file($configFile)){
            try{
                if(is_array($list = include($configFile))){
                    return $list;
                }else{
                    return [];
                }
            }catch(\Exception $e){
                return [];
            }
        }else{
            return [];
        }
    }
}
