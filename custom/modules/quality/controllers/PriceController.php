<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 17:05
 */

namespace custom\modules\quality\controllers;


use common\controllers\Controller;
use custom\modules\quality\models\PriceModel;

class PriceController extends Controller
{


    public $access = [
        'index'=>[null,'get'],
        'get-car-brand'=>[null,'get'],
        'get-car-type'=>[null,'get'],
        'get-car-factor'=>[null,'get'],
    ];

    public $actionUsingDefaultProcess = [
        'get-car-brand'=>PriceModel::SCE_CAR_BRAND,
        'get-car-type'=>PriceModel::SCE_CAR_TYPE,
        'get-car-factor'=>PriceModel::SCE_CAR_FACTOR,
        '_model'=>'\custom\modules\quality\models\PriceModel',
    ];

    //报价查询
    public function actionIndex(){
        throw new \yii\web\NotFoundHttpException;
        return $this->render('index');
    }

}
