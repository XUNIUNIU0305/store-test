<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/4/18
 * Time: 上午11:48
 */

namespace admin\components\handler;


use common\ActiveRecord\CustomUserStatementAR;
use common\components\handler\Handler;
use yii\data\ActiveDataProvider;

class StatementHandler extends Handler
{

    public static function provideStatements($currentPage =1,$pageSize =1 ,$searchData = []){

        return new ActiveDataProvider([
            'query' => CustomUserStatementAR::find()->select(['id'])->where($searchData)->asArray(),
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