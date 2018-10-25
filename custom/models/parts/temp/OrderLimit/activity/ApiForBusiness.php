<?php
namespace custom\models\parts\temp\OrderLimit\activity;

use Yii;
use yii\base\Object;
use yii\data\ActiveDataProvider;
use common\ActiveRecord\CustomUserActivityLimitAR;
use custom\models\parts\temp\OrderLimit\ActivityLimit;

class ApiForBusiness extends Object{

    public static function exchange($pickId, $businessAreaId, $return = 'throw'){
        $activityAR = CustomUserActivityLimitAR::findOne(['pick_id' => $pickId]);
        return Yii::$app->RQ->AR($activityAR)->update([
            'business_area_id' => $businessAreaId,
            'pick_datetime' => Yii::$app->time->fullDate,
            'pick_unixtime' => Yii::$app->time->unixTime,
        ]);
    }

    public static function provideList($businessAreaId, int $currentPage, int $pageSize){
        if($currentPage < 1)$currentPage = 1;
        if($pageSize < 1)$pageSize = 1;
        return new ActiveDataProvider([
            'query' => CustomUserActivityLimitAR::find()->
                select(['id', 'pick_id', 'custom_user_id', 'pay_datetime', 'pick_datetime'])->
                where(['business_area_id' => $businessAreaId])->
                andWhere(['paid' => ActivityLimit::STATUS_PAID])->
                asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'pick_datetime' => SORT_DESC,
                ],
            ],
        ]);
    }
}
