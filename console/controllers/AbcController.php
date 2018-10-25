<?php
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;
use common\models\parts\trade\recharge\abc\Abc;

class AbcController extends Controller{

    public function actionClearToken(){
        if(Yii::$app->cache->delete(Abc::TOKEN_KEY)){
            $this->stdout('cleared' . PHP_EOL);
        }else{
            $this->stdout('clear failed' . PHP_EOL);
        }
    }

    public function actionBatchCreateAbcAccount($quantity){
        if(!is_numeric($quantity) || $quantity <= 0){
            $this->stdout('error quantity' . PHP_EOL);
            return 0;
        }
        $offset = 0;
        $createdQuantity = 0;
        while(true){
            $account = Yii::$app->RQ->AR(new \common\ActiveRecord\CustomUserAR)->scalar([
                'select' => ['account'],
                'where' => [
                    'authorized' => 1,
                    'level' => [3, 4],
                    'status' => 0,
                ],
                'orderBy' => [
                    'id' => SORT_ASC,
                ],
                'offset' => $offset,
                'limit' => 1,
            ]);
            ++$offset;
            if(!$account){
                $this->stdout('账号已搜索完毕！' . PHP_EOL);
                return 0;
            }
            $isExist = Yii::$app->RQ->AR(new \common\ActiveRecord\AbchinaAccountAR)->exists([
                'where' => [
                    'user_account' => $account,
                ],
            ]);
            if($isExist)continue;
            $result = \common\models\parts\trade\recharge\abc\account\AbchinaAccount::generate($account, 1, false);
            if($result){
                $this->stdout("创建账号：[{$account}]成功！" . PHP_EOL);
                --$quantity;
                ++$createdQuantity;
                sleep(1);
            }else{
                $this->stdout("创建账号：[{$account}]失败。" . PHP_EOL);
            }
            if($quantity <= 0){
                $this->stdout("完成新建账号[{$createdQuantity}]个！" . PHP_EOL);
                break;
            }
        }
    }
}
