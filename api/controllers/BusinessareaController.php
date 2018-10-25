<?php
namespace api\controllers;

use Yii;
use api\models\BusinessareaModel;

class BusinessareaController extends Controller{

    protected $access = [
        'list' => [null, 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'list' => BusinessareaModel::SCE_GET_AREA_LIST,
        '_model' => '\api\models\BusinessareaModel',
    ];
}
