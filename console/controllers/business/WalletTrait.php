<?php
namespace console\controllers\business;

use Yii;
use common\ActiveRecord\BusinessUserAR;
use common\ActiveRecord\BusinessUserWalletAR;

trait WalletTrait{

    public function actionCreateWallet(){
        $allUsersId = Yii::$app->RQ->AR(new BusinessUserAR)->column([
            'select' => ['id'],
        ]);
        $walletExist = Yii::$app->RQ->AR(new BusinessUserWalletAR)->column([
            'select' => ['business_user_id'],
        ]);
        $creatingWalletQuantity = 0;
        foreach($allUsersId as $userId){
            if(in_array($userId, $walletExist))continue;
            try{
                $result = Yii::$app->RQ->AR(new BusinessUserWalletAR)->insert([
                    'business_user_id' => $userId,
                ]);
                if($result)$walletExist[] = $userId;
                $creatingWalletQuantity++;
            }catch(\Exception $e){
                $this->stdout("Creating wallet was aborted, [{$creatingWalletQuantity}]wallets had been created\n");
                return 0;
            }
        }
        $this->stdout("Creating wallet finished sucessfully, [{$creatingWalletQuantity}]wallets had been created\n");
        return 0;
    }
}
