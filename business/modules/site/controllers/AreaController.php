<?php
namespace business\modules\site\controllers;

use Yii;
use business\controllers\Controller;
use business\modules\site\models\AreaModel;

class AreaController extends Controller{

    protected $access = [
        'index' => ['249', 'get'],
        'chart' => ['249', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'chart' => AreaModel::SCE_GET_AREA_CHART,
        '_model' => '\business\modules\site\models\AreaModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
