<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/2
 * Time: 11:17
 */
namespace common\components\handler\supply;
use common\ActiveRecord\SupplyUserAR;
use common\components\handler\Handler;
use yii\data\ActiveDataProvider;

class SupplyUserHandler extends Handler
{
    //查询商户列表
    public static function search(int $pageSize,int $currentPage,$orderBy=['id'=>SORT_DESC]){
        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 1;
        return new ActiveDataProvider([
            'query' => SupplyUserAR::find()->select('id')->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => $orderBy,
            ],
        ]);
    }
}