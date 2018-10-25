<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/9/22
 * Time: 下午3:15
 */

namespace custom\controllers;


use common\controllers\Controller;
use custom\models\IndexProductModel;

class IndexProductController extends Controller
{
    protected $access = [
        'goods'=>[null,'get'],
    ];
    protected $actionUsingDefaultProcess = [
        'goods'=>IndexProductModel::SCE_GOODS,
        '_model'=>'custom\models\IndexProductModel'
    ];

}