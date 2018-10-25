<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/22
 * Time: 17:50
 */

namespace custom\controllers;


use common\controllers\Controller;
use custom\models\ForgetModel;

class ForgetController extends Controller
{
    public $layout = 'global';


    protected $access = [
        'index' => ['?', 'get'],
        'modify' => ['?', 'post']
    ];

    protected $actionUsingDefaultProcess = [
        'modify' => ForgetModel::SCE_MODIFY_PASSWORD,
        '_model' => '\custom\models\ForgetModel',
    ];


    public function actionIndex()
    {
        return $this->render("index");
    }

}