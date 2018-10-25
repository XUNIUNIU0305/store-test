<?php
namespace api\controllers;
use api\models\KuaidihundredModel;

class KuaidihundredController extends Controller
{
    protected $access = [
        'subscribe'=>[null,'post'],
    ];

    protected  $actionUsingDefaultProcess = [
        'subscribe'=>KuaidihundredModel::SCE_SUBSCRIBE,
        '_model'=>'api\models\KuaidihundredModel',
    ];
}