<?php
namespace admin\modules\fund\models\parts;

use Yii;
use yii\base\Object;
use yii\data\ActiveDataProvider;
use common\ActiveRecord\NonTransactionDepositAndDrawAR;

class DepositAndDrawList extends Object{

    public static function provideList($currentPage, $pageSize, int $status = null){
        if(!$currentPage = (int)$currentPage)$currentPage = 1;
        if(!$pageSize = (int)$pageSize)$pageSize = 1;
        $query = NonTransactionDepositAndDrawAR::find()->
            select(['id'])->
            filterWhere(['status' => $status])->
            asArray();
        return new ActiveDataProvider([
            'query' => $query,
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
