<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/4/7
 * Time: 下午2:04
 */

namespace admin\components\handler\info;


use common\ActiveRecord\OrderAR;
use common\components\handler\Handler;
use yii\data\ActiveDataProvider;

class OrderHandler extends Handler
{
    public static function provideOrders( $currentPage, $pageSize,$searchData = []){
        if(!$currentPage = (int)$currentPage)$currentPage = 1;
        if(!$pageSize = (int)$pageSize)$pageSize = 1;
        return new ActiveDataProvider([
            'query' => OrderAR::find()->select(['id'])->where($searchData)->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);
    }
}