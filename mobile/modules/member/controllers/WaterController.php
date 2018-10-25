<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/8/3
 * Time: 上午10:53
 */

namespace mobile\modules\member\controllers;


use mobile\modules\member\models\WaterModel;

class WaterController extends Controller
{
    protected $access = [
        'index'=>['@','get'],
        'list'=>['@','get'],
    ];
    protected $actionUsingDefaultProcess = [
        'list'=>WaterModel::SCE_GET_LIST,
        '_model'=>'mobile\modules\member\models\WaterModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }
}