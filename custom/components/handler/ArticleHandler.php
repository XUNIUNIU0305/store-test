<?php
namespace custom\components\handler;

use common\ActiveRecord\AdminArticleAR;
use common\ActiveRecord\CustomerArticleFooterAR;
use common\components\handler\Handler;
use yii\data\ActiveDataProvider;
use Yii;


class ArticleHandler extends Handler {
    public static function provideArticleList($currentPage, $pageSize,$where){
        if(!$currentPage = (int)$currentPage)$currentPage = 1;
        if(!$pageSize = (int)$pageSize)$pageSize = 1;
        return new ActiveDataProvider([
            'query' => AdminArticleAR::find()->select(['id','title','file_name','path','create_time'])->where($where)->asArray(),
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
