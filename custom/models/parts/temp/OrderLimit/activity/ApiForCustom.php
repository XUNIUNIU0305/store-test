<?php
namespace custom\models\parts\temp\OrderLimit\activity;

use Yii;
use yii\base\Object;
use yii\data\ActiveDataProvider;
use common\ActiveRecord\CustomUserActivityLimitAR;
use custom\models\parts\temp\OrderLimit\ActivityLimit;

class ApiForCustom extends Object{

    public static function provideList($userId, int $currentPage, int $pageSize){
        if($currentPage < 1)$currentPage = 1;
        if($pageSize < 1)$pageSize = 1;
        return new ActiveDataProvider([
            'query' => CustomUserActivityLimitAR::find()->
                select(['id', 'pick_id', 'pay_datetime', 'pick_datetime', 'business_area_id'])->
                where(['custom_user_id' => $userId])->
                andWhere(['paid' => ActivityLimit::STATUS_PAID])->
                asArray(),
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
