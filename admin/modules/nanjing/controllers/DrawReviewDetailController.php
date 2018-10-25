<?php
namespace admin\modules\nanjing\controllers;

use Yii;
use admin\controllers\Controller;
use admin\modules\nanjing\models\DrawReviewDetailModel;

class DrawReviewDetailController extends Controller{

    protected $access = [
        'detail' => ['@', 'get'],
        'pass' => ['@', 'post'],
        'reject' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'detail' => [
            'scenario' => DrawReviewDetailModel::SCE_GET_DETAIL,
            'convert' => false,
        ],
        'pass' => DrawReviewDetailModel::SCE_PASS,
        'reject' => DrawReviewDetailModel::SCE_REJECT,
        '_model' => DrawReviewDetailModel::class,
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
