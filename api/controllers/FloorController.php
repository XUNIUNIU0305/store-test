<?php
namespace api\controllers;

use api\models\FloorModel;
use api\controllers\Controller;

class FloorController extends Controller
{
    protected $access = [
        'get-floors' => [
            null,
            'get'
        ],
    ];

    protected $actionUsingDefaultProcess = [
        'get-floors'    => ['scenario' => FloorModel::FL_GET_FLOORS,'convert' => false],      
        '_model' => '\api\models\FloorModel',
    ];
}
