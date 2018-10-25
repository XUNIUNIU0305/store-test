<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/16
 * Time: 13:24
 */

namespace admin\modules\site\controllers;


use admin\controllers\Controller;
use admin\modules\site\models\AdmindepartmentModel;

class DepartmentController extends Controller
{

    protected $access = [
        'list' => ['@', 'get'],
        'add' => ['@', 'post'],
        'modify' => ['@', 'post'],
        'delete' => ['@', 'post'],
        'getdepartmentlist' => ['@', 'get'],
        'getemployeelist' => ['@', 'get'],
    ];


    protected $actionUsingDefaultProcess = [
        'list' => AdmindepartmentModel::SCE_GET_DEPARTMENT_LIST,
        'add' => AdmindepartmentModel::SCE_ADD_DEPARTMENT,
        'delete' => AdmindepartmentModel::SCE_DEL_DEPARTMENT,
        'modify' => AdmindepartmentModel::SCE_MODIFY_DEPARTMENT,
        'getemployeelist' => AdmindepartmentModel::SCE_GET_DEPARTMENT_EMPLOYEE,
        'getdepartmentlist' => AdmindepartmentModel::SCE_GET_DEPARTMENT_LIST,
        '_model' => '\admin\modules\site\models\AdmindepartmentModel',
    ];



    //部门输出页面
    public function actionIndex(){
        return $this->render("index");
    }

}