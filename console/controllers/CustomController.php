<?php
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\AdminPermissionAR;
use common\ActiveRecord\AdminRolePermissionAR;
use common\ActiveRecord\CustomUserRegistercodeAR;
use common\ActiveRecord\CustomAccountSecondaryMenuAR;

class CustomController extends Controller{

    /**
     * 设置指定账号为体系内权限
     */
    public function actionSetLevel(){
        $file = __DIR__ . '/user_list.php';
        if(file_exists($file)){
            $list = include($file);
        }else{
            $this->stdout('list is not exist' . PHP_EOL);
            return 0;
        }
        $successfulQuantity = 0;
        $failedQuantity = 0;
        foreach($list as $account){
            $this->stdout("Modifying Account: [{$account}]..." . PHP_EOL);
            $user = CustomUserAR::findOne([
                'account' => $account,
            ]);
            if(!$user){
                ++$failedQuantity;
                $this->stdout("Account: [{$account}] is not exist." . PHP_EOL . PHP_EOL);
                continue;
            }
            $result = Yii::$app->RQ->AR($user)->update([
                'level' => \common\models\parts\custom\CustomUser::LEVEL_IN_SYSTEM,
            ]);
            if($result){
                ++$successfulQuantity;
                $this->stdout("Modify Account: [{$account}] success!" . PHP_EOL);
            }else{
                ++$failedQuantity;
                $this->stdout("Modify Account: [{$account}] failed, original level: {$user->level}." . PHP_EOL);
            }
            $this->stdout(PHP_EOL);
        }
        $this->stdout("All Accounts have been handled, {$successfulQuantity} success, {$failedQuantity} failed." . PHP_EOL);
        return 0;
    }

    /**
     * 设置除运营商权限外的所有账号为邀请权限账号
     */
    public function actionResetLevel(){
        $result = CustomUserAR::updateAll([
            'level' => 2,
        ], [
            'level' => 3,
        ]);
        if($result){
            $this->stdout('reset success!' . PHP_EOL);
        }else{
            $this->stdout('reset failed!' . PHP_EOL);
        }
        return 0;
    }

    /**
     * 重置权限，已使用
     */
    public function actionDeleteFunctionAuthorization(){
        return 0;
        $mainPermissionId = 128;
        $secondaryPermissionIds = Yii::$app->RQ->AR(new AdminPermissionAR)->column([
            'select' => ['id'],
            'where' => [
                'parent' => $mainPermissionId,
            ],
        ]);
        $allPermissionIds = array_merge($secondaryPermissionIds, [$mainPermissionId]);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            AdminRolePermissionAR::deleteAll([
                'admin_permission_id' => $allPermissionIds,
            ]);
            AdminPermissionAR::deleteAll([
                'id' => $allPermissionIds,
            ]);
            CustomUserAR::updateAll([
                'expire_datetime' => '2099-12-31 23:59:59',
                'expire_unixtime' => '4102415999',
                'authorized' => 1,
            ]);
            //CustomUserAR::updateAll([
                //'level' => 2,
            //], [
                //'level' => 3,
            //]);
            CustomUserRegistercodeAR::updateAll([
                'level' => 2,
                'authorized' => 1,
            ], [
                'used' => 0,
            ]);
            CustomAccountSecondaryMenuAR::findOne([
                'title' => '递交审核信息',
            ])->delete();
            $transaction->commit();
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->stdout('delete permission failed');
        }
        return 0;
    }
}
