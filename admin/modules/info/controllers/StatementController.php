<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/4/18
 * Time: 上午11:03
 */

namespace admin\modules\info\controllers;


use admin\controllers\Controller;
use admin\modules\info\models\StatementModel;

class StatementController extends  Controller
{
    public $access = [
        'index'=>['@','get'],
        'list'=>['@','get'],
    ];
    public $actionUsingDefaultProcess = [
        'list'=>StatementModel::SCE_STATEMENT_LIST,
        '_model' => 'admin\modules\info\models\StatementModel',
    ];


    public function actionIndex(){
        return $this->render('index');
    }
}