<?php
namespace console\controllers\business;

use Yii;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\BusinessAreaAR;
use business\models\parts\Area;
use common\models\parts\custom\CustomUser;

trait CountCustomQuantityTrait{

    public function actionCountCustomQuantity(){
        $areaCount = Yii::$app->RQ->AR(new CustomUserAR)->all([
            'select' => ['business_area_id', 'count' => 'count(*)'],
            'where' => [
                'status' => CustomUser::STATUS_NORMAL,
            ],
            'groupBy' => ['business_area_id'],
        ]);
        $fifthAreaCountWhichGetCustomer = array_column($areaCount, 'count', 'business_area_id');
        $fifthAreaCount = $this->generateAllFifthAreaCount($fifthAreaCountWhichGetCustomer);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $this->countFifthCustomQuantity($fifthAreaCount);
            $this->countFirstToFourthCustomQuantity();
            $transaction->commit();
            return 0;
        }catch(\Exception $e){
            $transaction->rollBack();
            return 1;
        }
    }

    private function generateAllFifthAreaCount(array $count){
        $fifthAreaId = Yii::$app->RQ->AR(new BusinessAreaAR)->column([
            'select' => ['id'],
            'where' => ['level' => Area::LEVEL_FIFTH],
        ]);
        $fifthArea = array_fill_keys($fifthAreaId, 0);
        return ($count + $fifthArea);
    }

    private function countFifthCustomQuantity(array $count, $return = 'throw'){
        if(empty($count))return true;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($count as $areaId => $quantity){
                Yii::$app->RQ->AR(BusinessAreaAR::findOne($areaId))->update([
                    'custom_quantity' => $quantity,
                ]);
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    private function countFirstToFourthCustomQuantity($return = 'throw'){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            for($i = Area::LEVEL_QUATERNARY; $i >= Area::LEVEL_TOP; $i--){
                $areaIds = Yii::$app->RQ->AR(new BusinessAreaAR)->column([
                    'select' => ['id'],
                    'where' => [
                        'level' => $i,
                    ],
                ]);
                foreach($areaIds as $areaId){
                    $childCustomQuantity = Yii::$app->RQ->AR(new BusinessAreaAR)->sum([
                        'where' => [
                            'parent_business_area_id' => $areaId,
                        ],
                    ], 'custom_quantity') ?? 0;
                    Yii::$app->RQ->AR(BusinessAreaAR::findOne($areaId))->update([
                        'custom_quantity' => $childCustomQuantity,
                    ]);
                }
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }
}
