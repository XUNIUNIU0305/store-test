<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 17-3-3
 * Time: 下午1:55
 */

namespace admin\modules\site\controllers;


use admin\controllers\Controller;
use admin\modules\site\models\AdminroleModel;

class AdminroleController extends Controller
{


    protected $access = [
        'getrolelist' => ['@', 'get'], //首页
        'add' => ['@', 'post'], //添加角色
        'modify' => ['@', 'post'], //编辑角色
        'delete'=>['@','post'],//删除角色
        'getbinduser'=>['@','get'],//获取角色绑定的用户
        'getpermissionlist' => ['@', 'get'], //获取权限列表
        'bindpermission' => ['@', 'post'],//绑定权限
        'revokepermission' => ['@', 'post'],//取消权限
        'getrolepermission' => ['@', 'get'],//'获取角色对应权限列表
    ];


    protected $actionUsingDefaultProcess = [
        'getrolelist' => AdminroleModel::SCE_GET_ROLE_LIST,
        'add' => AdminroleModel::SCE_ADD_ROLE,
        'modify' => AdminroleModel::SCE_MODIFY_ROLE,
        'delete'=>AdminroleModel::SCE_DEL_ROLE,
        'getbinduser'=>AdminroleModel::SCE_GET_ROLE_BIND_USER,
        'getpermissionlist' => AdminroleModel::SCE_GET_PERMISSION_LIST,
        'bindpermission' => AdminroleModel::SCE_BIND_PERMISSION,
        'revokepermission' => AdminroleModel::SCE_REVOKE_PERMISSION,
        'getrolepermission' => AdminroleModel::SCE_GET_ROLE_PERMISSION,
        '_model' => '\admin\modules\site\models\AdminroleModel',
    ];




    //角色视图展示
    public function actionIndex(){


        return $this->render("index");
    }



}