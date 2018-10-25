<?php
namespace console\controllers\nanjing;

use Yii;
use common\models\parts\trade\recharge\nanjing\Nanjing;
use common\models\parts\trade\recharge\nanjing\account\MainAccount;
use common\models\parts\trade\recharge\nanjing\account\NanjingAccount;

trait QueryBalanceTrait{

    public function actionQueryBalance($accountId = null){
        $nanjing = new Nanjing;
        if($accountId === null){
            $account = $nanjing->mainAccount;
        }else{
            try{
                $account = new NanjingAccount([
                    'id' => $accountId,
                ]);
            }catch(\Exception $e){
                $this->stdout("account error\n");
                return 0;
            }
        }
        $balance = $nanjing->queryBalance($account, false);
        if($balance){
            if($balance->isSuccess()){
                $this->stdout('user account balance: ' . $balance->List[0]['Amount'] . "\n");
            }else{
                $this->stdout("query error\n");
                return 0;
            }
        }else{
            $this->stdout("query error\n");
            return 0;
        }
    }
}
