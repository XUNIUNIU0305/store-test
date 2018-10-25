<?php
namespace custom\controllers;

use custom\models\IndexBrandModel;
use api\controllers\Controller;

class IndexBrandController extends Controller
{
    protected $access = [
        'get-brands' => [
            null,
            'get'
        ],
    ];

    protected $actionUsingDefaultProcess = [
        'get-brands'    => ['scenario' => IndexBrandModel::BR_GET_BRANDS,'convert' => false],      
        '_model' => '\custom\models\IndexBrandModel',
    ];
}