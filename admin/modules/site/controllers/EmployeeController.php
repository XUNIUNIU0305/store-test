<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/9
 * Time: 下午6:58
 */

namespace admin\modules\site\controllers;


use admin\modules\site\models\EmployeeModel;
use admin\controllers\Controller;

class EmployeeController extends Controller
{
    protected $access = [
        'list' => [
            '@',
            'get'
        ],
        'add-employee' => [
            '@',
            'post'
        ],

        'remove-employee' => [
            '@',
            'post'
        ],

        'edit-employee' => [
            '@',
            'post'
        ],




    ];


    protected $actionUsingDefaultProcess = [
        'list' => EmployeeModel::SCE_LIST_EMPLOYEE,
        'add-employee' => EmployeeModel::SCE_ADD_EMPLOYEE,
        'remove-employee' => EmployeeModel::SCE_REMOVE_EMPLOYEE,
        'edit-employee' => EmployeeModel::SCE_EDIT_EMPLOYEE,

         '_model' => '\admin\modules\site\models\EmployeeModel',

    ];


    public function actionIndex(){
        return $this->render('index');
    }
}