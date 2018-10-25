<?php
namespace admin\modules\nanjing\controllers;

use Yii;
use admin\controllers\Controller;
use admin\modules\nanjing\models\DrawReviewListModel;

class DrawReviewListController extends Controller{

    protected $access = [
        'list' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'list' => [
            'scenario' => DrawReviewListModel::SCE_GET_LIST,
            'convert' => false,
        ],
        '_model' => DrawReviewListModel::class,
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
