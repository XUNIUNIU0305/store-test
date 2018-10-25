<?php
namespace custom\components;

use common\ActiveRecord\CustomUserStatementAR;
use Yii;
use yii\base\Object;
use yii\base\InvalidCallException;
use yii\data\ActiveDataProvider;


class UserStatement extends Object{

    protected $userId;

    public function init(){
        if(Yii::$app->user->isGuest)throw new InvalidCallException;
        $this->userId = Yii::$app->user->id;
    }

    public function provideStatements($currentPage, $pageSize,$searchData = null){
        if(!$currentPage = (int)$currentPage)$currentPage = 1;
        if(!$pageSize = (int)$pageSize)$pageSize = 1;
        return new ActiveDataProvider([
            'query' => CustomUserStatementAR::find()->select(['id'])->where([
                'custom_user_id' => $this->userId,
            ])->andWhere(is_null($searchData) ? [] : $searchData)->asArray(),
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
