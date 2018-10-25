<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/5/15
 * Time: 下午5:26
 */

namespace admin\modules\site\controllers;


use admin\controllers\Controller;
use admin\modules\site\models\UserModel;

class UserController extends Controller
{
    protected $access = [
        'index' => ['@', 'get'],
        'user-info' => ['@', 'get'],
        'order-info' => ['@', 'get'],
        'cancel-account' => ['@', 'post'],
        'reset-password' => ['@', 'post'],
        'unbind-mobile' => ['@', 'post'],
        'upgrade-user' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'user-info' => UserModel::SCE_USER_INFO,
        'order-info' => UserModel::SCE_ORDER_INFO,
        'cancel-account' => UserModel::SCE_CANCEL_ACCOUNT,
        'reset-password' => UserModel::SCE_RESET_PASSWORD,
        'unbind-mobile' => UserModel::SCE_UNBIND_MOBILE,
        'upgrade-user' => UserModel::SCE_UPGRADE_USER,
        '_model' => '\admin\modules\site\models\UserModel',
    ];

    public function actionIndex()
    {
        return $this->render('index');
    }


}
