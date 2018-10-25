<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 17-3-3
 * Time: 下午1:55
 */

namespace admin\modules\site\controllers;


use admin\controllers\Controller;
use admin\modules\site\models\AdminuserModel;

class AdminuserController extends Controller
{


    protected $access = [
        'index' => ['@', 'get'], //首页
        'add' => ['@', 'post'], //添加用户
        'modify' => ['@', 'post'], //编辑用户
        'remove'=>['@','post'],//删除用户
        'getuserlist' => ['@', 'get'], //编辑用户信息
        'getuserroles' => ['@', 'get'],//获取用户角色信息
    ];


    protected $actionUsingDefaultProcess = [
        'add' => AdminuserModel::SCE_ADD_USER,
        'modify' => AdminuserModel::SCE_MODIFY_USER,
        'getuserlist' => AdminuserModel::SCE_GET_USER_LIST,
        'getuserroles' => AdminuserModel::SCE_GET_USER_ROLES,
        'remove'=>AdminuserModel::SCE_DELETE_USER,
        '_model' => '\admin\modules\site\models\AdminuserModel',
    ];


    //用户信息管理展示页面
    public function actionIndex(){
        return $this->render("index");
    }

}