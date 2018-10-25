<?php
namespace api\controllers;

use api\models\KeywordModel;
use api\controllers\Controller;

class KeywordController extends Controller
{
    protected $access = [
        'get-keywords' => [
            null,
            'get'
        ],
    ];

    protected $actionUsingDefaultProcess = [
        'get-keywords'    => ['scenario' => KeywordModel::KW_GET_KEYWORDS,'convert' => false],      
        '_model' => '\api\models\KeywordModel',
    ];
}
