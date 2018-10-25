<?php
namespace admin\controllers;


use Yii;

use admin\models\MenuModel;

class MenuController extends \common\controllers\Controller
{

    protected $access = [
        'top' => ['@', 'get'],
        'secondary' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'secondary' => MenuModel::SCE_GET_SECONDARY_MENU,
        '_model' => '\admin\models\MenuModel',
    ];

    public function actionTop()
    {
        return $this->success(MenuModel::getTopMenu());
    }
}
