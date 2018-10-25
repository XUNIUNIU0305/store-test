<?php
namespace api\controllers;

use Yii;
use api\controllers\Controller;
use api\models\CatelogModel;

class CatelogController extends Controller
{

    protected $access = [
        'get-columns'   => [null, 'get'],
        'get-brands'    => [null, 'get'],
        'get-items'     => [null, 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'get-columns' => [
            'scenario' => CatelogModel::SCE_GET_COLUMNS,
            'convert' => false,
        ],
        'get-brands' => [
            'scenario' => CatelogModel::SCE_GET_BRANDS,
            'convert' => false,
        ],
        'get-items' => [
            'scenario' => CatelogModel::SCE_GET_ITEMS,
            'convert' => false,
        ],
        '_model' => '\api\models\CatelogModel',
    ];
}
