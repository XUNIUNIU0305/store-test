<?php
namespace admin\components\handler;


use admin\models\parts\business\Business;
use common\ActiveRecord\BusinessQuaternaryAreaAR;
use common\ActiveRecord\BusinessSecondaryAreaAR;
use common\ActiveRecord\BusinessTertiaryAreaAR;
use common\ActiveRecord\BusinessTopAreaAR;
use common\components\handler\Handler;
use Yii;

class BusinessHandler extends Handler
{
    public static function create($areaId, $createOption)
    {

        if ($areaId == Business::PROVINCE && Yii::$app->RQ->AR(new BusinessTopAreaAR())->exists(['where' => ['title' => $createOption['title']], 'limit' => 1]))return false;
        if (Yii::$app->RQ->AR((new Business(['area_id' => $areaId]))->getAR())->insert($createOption))
        {
            return true;
        }

        return false;
    }


    public static function delete($id, $areaId)
    {
        if ($areaId == Business::GROUP)
        {
            Yii::$app->RQ->AR((new Business([
                'id' => $id,
                'area_id' => $areaId
            ]))->getAR())->delete();

            return true;
        }
        if ((new Business(['area_id' => $areaId + 1]))->isExist($id) && Yii::$app->RQ->AR((new Business([
                'id' => $id,
                'area_id' => $areaId
            ]))->getAR())->delete()
        )
        {
            return true;
        }
        return false;
    }

    //获取省数据
    public static function getProvince(){
        return Yii::$app->RQ->AR(new BusinessTopAreaAR())->all([
            'select'=>['id','title'],
        ]);
    }


    public static function getCity($top){
        if (empty($top)) return false;
        return  Yii::$app->RQ->AR(new BusinessSecondaryAreaAR())->all([
            'select'=>['id','title'],
            'where'=>['business_top_area_id'=>$top],
        ]);
    }

    public static function getArea($top,$secondary){
        if (empty($top) || empty($secondary)) return false;
        return  Yii::$app->RQ->AR(new BusinessTertiaryAreaAR())->all([
            'select'=>['id','title'],
            'where'=>[
                'business_top_area_id'=>$top,
                'business_secondary_area_id'=>$secondary,
            ],
        ]);
    }

    public static function getGroup($top,$secondary,$tertiary){
        if (empty($top) || empty($secondary) || empty($tertiary)) return false;
        return  Yii::$app->RQ->AR(new BusinessQuaternaryAreaAR())->all([
            'select'=>['id','title'],
            'where'=>[
                'business_top_area_id'=>$top,
                'business_secondary_area_id'=>$secondary,
                'business_tertiary_area_id'=>$tertiary,
            ],
        ]);
    }



}
