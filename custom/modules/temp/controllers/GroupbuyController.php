<?php
namespace custom\modules\temp\controllers;

use Yii;
use common\controllers\Controller;
use custom\modules\temp\models\GroupbuyModel;

class GroupbuyController extends Controller
{
    protected $access = [
        'get-all-groupbuy'              => ['@', 'get'],
        'is-groupbuy'                   => ['@', 'get'],
        'get-all-groupbuy-specific'     => ['@', 'get'],
        'get-unix-timestamp'            => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'get-all-groupbuy'          => GroupbuyModel::SCE_GET_ALL_GROUPBUY,
        'get-all-groupbuy-specific' => GroupbuyModel::SCE_GET_ALL_GROUPBUY_SPECIFIC,
        'is-groupbuy'               => GroupbuyModel::SCE_IS_GROUPBUY,
        '_model'                    => GroupbuyModel::class,
    ];

    public function actionIndex()
    {
        if(Yii::$app->user->getIsGuest()) {
            return $this->redirect('/login/index');
        }
        
        $this->module->layout = false;
        return $this->render('index');
    }

    public function actionGetUnixTimestamp()
    {
        return $this->success(['unix_timestamp' => time()]);
    }
}
