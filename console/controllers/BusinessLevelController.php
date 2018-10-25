<?php
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;
use common\ActiveRecord\BusinessAreaLevelAR;
use common\ActiveRecord\BusinessUserAR;
use common\ActiveRecord\BusinessSecondaryMenuAR;
use common\ActiveRecord\CustomUserRegistercodeAR;
use common\ActiveRecord\BusinessAreaAR;
use common\ActiveRecord\MembraneOrderAR;
use business\models\parts\Area;
use business\models\parts\AreaLevel;
use business\models\parts\Role;
use business\models\parts\Account;
use common\models\parts\MembraneOrder;

class BusinessLevelController extends Controller{

    public function actionModify(){
        return 0;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if($this->modifyBusinessRole() &&
                $this->modifyBusinessLevel() &&
                $this->modifyBusinessMenu() &&
                $this->modifyBusinessDefaultAreaName() &&
                $this->resetMembraneOrder() &&
                $this->deleteRegisterCode()
            ){
                $transaction->commit();
                $this->stdout('ok' . PHP_EOL);
                return true;
            }
        }catch(\Exception $e){
            $transaction->rollBack();
            var_dump($e->getMessage());exit;
            $this->stdout('error' . PHP_EOL);
            return false;
        }
    }

    private function resetMembraneOrder(){
        $orderIds = Yii::$app->RQ->AR(new MembraneOrderAR)->column([
            'select' => ['id'],
            'where' => [
                'status' => [
                    MembraneOrder::STATUS_DEFAULT,
                    MembraneOrder::STATUS_PAYED,
                    MembraneOrder::STATUS_ACCEPTED,
                ],
            ],
        ]);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($orderIds as $orderId){
                $order = new MembraneOrder([
                    'id' => $orderId,
                ]);
                if($order->getStatus() == MembraneOrder::STATUS_DEFAULT ||
                    $order->getStatus() == MembraneOrder::STATUS_PAYED){
                    $order->customCancel();
                }
                if($order->getStatus() == MembraneOrder::STATUS_ACCEPTED){
                    $order->toFinish();
                }
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
    }

    private function modifyBusinessDefaultAreaName(){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            Yii::$app->RQ->AR(BusinessAreaAR::findOne(2))->update([
                'name' => '默认辅导区',
            ]);
            Yii::$app->RQ->AR(BusinessAreaAR::findOne(3))->update([
                'name' => '默认督导区',
            ]);
            Yii::$app->RQ->AR(BusinessAreaAR::findOne(4))->update([
                'name' => '默认运营商',
            ]);
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
    }

    private function deleteRegisterCode(){
        return (CustomUserRegistercodeAR::deleteAll('`business_area_id` <> 5 and `used` = 0') === false ? false : true);
    }

    private function modifyBusinessMenu(){
        foreach(BusinessSecondaryMenuAR::findAll([
            'level' => 100,
            'is_absolute' => 1,
        ]) as $AR){
            Yii::$app->RQ->AR($AR)->update([
                'level' => 50,
            ]);
        }
        return true;
    }

    //角色
    const SECONDARY_ROLE_NAME = '辅导员';
    const TERTIARY_ROLE_NAME = '督导';
    const QUATERNARY_ROLE_NAME = '运营商';

    private function modifyBusinessRole(){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $secondaryRole = new Role([
                'id' => Role::SECONDARY,
            ]);
            $secondaryRole->name = self::SECONDARY_ROLE_NAME;
            $tertiaryRole = new Role([
                'id' => Role::TERTIARY,
            ]);
            $tertiaryRole->name = self::TERTIARY_ROLE_NAME;
            $quaternaryRole = new Role([
                'id' => Role::QUATERNARY,
            ]);
            $quaternaryRole->name = self::QUATERNARY_ROLE_NAME;
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
    }

    //层级名称
    const SECONDARY_LEVEL_NAME = '辅导区';
    const TERTIARY_LEVEL_NAME = '督导区';
    const QUATERNARY_LEVEL_NAME = '运营公司';

    private function modifyBusinessLevel(){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $secondaryLevel = new AreaLevel([
                'level' => Area::LEVEL_SECONDARY,
            ]);
            $secondaryLevel->name = self::SECONDARY_LEVEL_NAME;
            $tertiaryLevel = new AreaLevel([
                'level' => Area::LEVEL_TERTIARY,
            ]);
            $tertiaryLevel->name = self::TERTIARY_LEVEL_NAME;
            $quaternaryLevel = new AreaLevel([
                'level' => Area::LEVEL_QUATERNARY,
            ]);
            $quaternaryLevel->name = self::QUATERNARY_LEVEL_NAME;
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
    }
}
