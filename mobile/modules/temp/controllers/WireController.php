<?php
namespace mobile\modules\temp\controllers;

use Yii;
use mobile\modules\temp\models\WireModel;
class WireController extends Controller{

    protected $access = [
        'index' => [null, 'get'],
        'get-car-brand-list' => [null, 'get'],
        'get-wire-list' => [null, 'get'],
        'get-car-type-list' => [null, 'get'],
        'get-wire-detail' => [null, 'get'],
        'get-wire-image' => [null, 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'get-car-brand-list' => WireModel::SCE_GET_CAR_BRAND_LIST,
        'get-wire-list' => WireModel::SCE_GET_WIRE_LIST,
        'get-car-type-list' => WireModel::SCE_GET_CAR_TYPE_LIST,
        'get-wire-detail' => WireModel::SCE_GET_WIRE_DETAIL,
        'get-wire-image' => WireModel::SCE_GET_WIRE_IMAGE,
        '_model' => WireModel::class,
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}
