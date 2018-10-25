<?php
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;
use common\models\parts\Order;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\CustomUserOrderLimitAR;
use common\ActiveRecord\OrderItemAR;
use custom\components\handler\OrderHandler;
use common\ActiveRecord\SupplyUserAR;
use common\ActiveRecord\SupplyUserWalletAR;
use common\ActiveRecord\CustomUserAR;

class TempController extends Controller{

    /**
     * 创建供应商账号
     */
    public function actionSupply($confirm = false){
        if($confirm !== 'create'){
            $this->stdout("enter parameter [create] if you want create an account\n");
            return 0;
        }
        do{
            $account = rand(100000000, 999999999);
        }while(SupplyUserAR::findOne(['account' => $account]));
        $passwd = 'A' . $account . 'z';
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $userId = Yii::$app->RQ->AR(new SupplyUserAR)->insert([
                'account' => $account,
                'passwd' => Yii::$app->security->generatePasswordHash($passwd),
            ]);
            Yii::$app->RQ->AR(new SupplyUserWalletAR)->insert([
                'supply_user_id' => $userId,
            ]);
            $transaction->commit();
            $this->stdout("create account success: {$account}");
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->stdout('create account failed');
        }
        $this->stdout("\n");
        return 0;
    }

    public function actionSetDefaultAvatar(){
        CustomUserAR::updateAll([
            'header_img' => Yii::$app->params['OSS_PostHost'] . '/a/avatar/default_avatar.jpg',
        ], [
            'header_img' => '',
        ]);
        return 0;
    }
}
