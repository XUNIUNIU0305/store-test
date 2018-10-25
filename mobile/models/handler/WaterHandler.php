<?php
namespace mobile\models\handler;
use common\ActiveRecord\CustomUserActivityLimitAR;
use common\components\handler\Handler;
use custom\models\parts\temp\OrderLimit\ActivityLimit;
use yii\data\ActiveDataProvider;
use Yii;

class WaterHandler extends Handler
{
    public static function waterList(int $used ,$currentPage,$pageSize){
        return new ActiveDataProvider([
            'query' => CustomUserActivityLimitAR::find()->
            select(['id', 'pick_id', 'pay_datetime', 'pick_datetime', 'business_area_id'])
                ->where(['custom_user_id' => Yii::$app->user->id,'paid' => ActivityLimit::STATUS_PAID])
                ->andWhere($used ? ['>','business_area_id',0] : ['business_area_id'=>$used])
                ->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'pick_datetime' => SORT_ASC,
                ],
            ],
        ]);
    }
}