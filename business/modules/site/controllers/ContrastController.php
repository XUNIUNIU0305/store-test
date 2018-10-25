<?php
namespace business\modules\site\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\site\models\ContrastModel;

class ContrastController extends Controller{

    protected $access = [
        'index' => ['249', 'get'],
        'chart' => ['249', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'chart' => ContrastModel::SCE_CONTRAST_AREA,
        '_model' => '\business\modules\site\models\ContrastModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
