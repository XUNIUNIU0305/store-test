<?php
namespace mobile\modules\temp\controllers;

use Yii;
use custom\modules\temp\models\RankModel;

class RankController extends Controller{

    public $layout = 'empty';

    protected $access = [
        'index' => ['@', 'get'],
        'full-rank' => ['@', 'get'],
        'user-rank' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'full-rank' => RankModel::SCE_GET_RANK,
        'user-rank' => RankModel::SCE_GET_USER_RANK,
        '_model' => RankModel::class,
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
