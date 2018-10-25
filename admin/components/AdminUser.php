<?php
namespace admin\components;


use admin\models\parts\role\AuthAssignment;
use admin\models\parts\role\AuthItem;
use common\ActiveRecord\AdminUserAR;
use Yii;
use common\components\basic\LoggedUserAbstract;


class AdminUser extends LoggedUserAbstract
{

    protected function getUserComponents()
    {
        return [
            'menus' => [
                'class' => 'admin\models\parts\role\AdminAccount',
                'id' => Yii::$app->user->id,
            ],
        ];
    }




}
