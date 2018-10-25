<?php
namespace business\models\handler;

use Yii;
use common\components\handler\Handler;
use common\ActiveRecord\BusinessUserAR;
use business\models\parts\Account;
use business\models\parts\Role;
use yii\data\ActiveDataProvider;

class AdminHandler extends Handler{

    public static function provide(bool $isAdmin, int $currentPage, int $pageSize, int $account = null){
        if($currentPage <= 0)$currentPage = 1;
        if($pageSize <= 0)$pageSize = 1;
        return new ActiveDataProvider([
            'query' => BusinessUserAR::find()->
                select(['id'])->
                where(['business_role_id' => $isAdmin ? Role::ADMIN : Role::UNDEFINED])->
                andWhere(['status' => [Account::STATUS_NORMAL, Account::STATUS_UNREGISTERED]])->
                andFilterWhere(['account' => $account]),
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
