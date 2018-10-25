<?php
namespace api\controllers;

use Yii;
use api\models\CategoryModel;

class CategoryController extends Controller{

    protected $access = [
        'category' => [null, 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'category' => [
            'scenario' => CategoryModel::SCE_GET_CATEGORY,
            'convert' => false,
        ],
        '_model' => '\api\models\CategoryModel',
    ];
}
