<?php
namespace admin\components\handler;

use admin\models\parts\business\Business;
use common\ActiveRecord\BusinessAreaLeaderAR;
use common\ActiveRecord\BusinessQuaternaryAreaAR;
use common\ActiveRecord\BusinessTertiaryAreaAR;
use common\components\handler\Handler;
use Yii;
use yii\data\ActiveDataProvider;

class EmployeeHandler extends Handler
{
    public static function create($employ)
    {
        if (!empty($employ) && Yii::$app->RQ->AR(new BusinessAreaLeaderAR())->insert($employ))
        {
            return true;
        }
        return false;

    }


    public static function delete($id)
    {

        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            if (!empty($id) && Yii::$app->RQ->AR(new BusinessAreaLeaderAR())->exists([
                    'where' => ['id' => $id],
                    'limit' => 1,
                ])
            )
            {
                Yii::$app->RQ->AR(BusinessAreaLeaderAR::findOne(['id' => $id]))->delete();
            }
            if (Yii::$app->RQ->AR(new BusinessTertiaryAreaAR())->exists([
                'where' => ['business_area_leader_id' => $id],
                'limit' => 1,
            ])
            )
            {
                BusinessTertiaryAreaAR::updateAll(['business_area_leader_id' => $id], 'business_area_leader_id = 1');
            }

            if (Yii::$app->RQ->AR(new BusinessQuaternaryAreaAR())->exists([
                'where' => ['business_area_leader_id' => $id],
                'limit' => 1,
            ])
            )
            {
                BusinessQuaternaryAreaAR::updateAll(['business_area_leader_id' => $id], 'business_area_leader_id = 1');
            }
            $transaction->commit();
            return true;
        }
        catch (\Exception $e)
        {
            $transaction->rollBack();
            return false;
        }
    }


    public static function provideEmployees($currentPage, $pageSize, $searchData = [])
    {
        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 1;
        return new ActiveDataProvider([
            'query' => BusinessAreaLeaderAR::find()->select([
                'id',
                'name',
                'mobile',
                'remark',
            ])->filterWhere(!isset($searchData) ? [] : $searchData)->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
        ]);
    }


}
