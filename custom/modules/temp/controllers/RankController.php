<?php
namespace custom\modules\temp\controllers;

use Yii;
use common\controllers\Controller;
use custom\modules\temp\models\RankModel;

class RankController extends Controller{

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
        $this->module->layout = 'empty';
        $this->module->setLayoutPath('@temp/views/layouts');
        return $this->render('index');
    }
}
