<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/15
 * Time: 16:54
 */

namespace admin\models\parts\role;


use common\ActiveRecord\AdminRoleAR;
use common\ActiveRecord\AdminRolePermissionAR;
use common\ActiveRecord\AdminUserRoleAR;
use yii\base\Exception;
use yii\base\InvalidCallException;
use yii\base\Object;
use Yii;

class AdminRole extends Object
{
    public $id;
    protected $AR;

    public function init()
    {
        if (!$this->id || !$this->AR = AdminRoleAR::findOne($this->id)) throw new InvalidCallException();
    }


    //获取角色名称
    public function getRoleName()
    {
        return $this->AR->role_name;
    }

    //获取角色描述
    public function getRoleIntroduction()
    {
        return $this->AR->role_introduction;
    }

    //设置角色名称
    public function setRoleName($name, $return = "throw")
    {
        return Yii::$app->RQ->AR($this->AR)->update(['role_name' => $name], $return) ;
    }

    //设置角色描述
    public function setRoleIntroduction($introduction, $return = "throw")
    {
        return Yii::$app->RQ->AR($this->AR)->update(['role_introduction' => $introduction], $return);
    }

    //一次性编辑角色信息
    public function setRoleInformation($data, $return = "throw")
    {
        return Yii::$app->RQ->AR($this->AR)->update($data, $return);
    }

    //查询是否绑定权限
    public function hasBindPermission()
    {
        return AdminRolePermissionAR::find()->where(['admin_role_id' => $this->id])->exists();
    }

    //检测是否绑定可用户
    public function hasBindUser()
    {
        return AdminUserRoleAR::find()->where(['admin_role_id' => $this->id])->exists();
    }

    //获取绑定该角色的用户列表
    public function getBindUserList(){

        return array_map(function($item){

            return new AdminAccount(['id'=>$item]);

        }, Yii::$app->RQ->AR(new AdminUserRoleAR())->column([
            'select'=>['admin_user_id'],
            'where'=>['admin_role_id'=>$this->id],
        ]));
    }


    //绑定权限
    public function bindPermission(AdminPermission $permission, $return = "throw")
    {
        return Yii::$app->RQ->AR(new AdminRolePermissionAR())->insert([
            'admin_role_id' => $this->id,
            'admin_permission_id' => $permission->id,
        ], $return);
    }

    //取消权限
    public function revokePermission(AdminPermission $permission, $return = "throw")
    {
        try {
            return Yii::$app->RQ->AR(AdminRolePermissionAR::findOne(['admin_role_id' => $this->id, 'admin_permission_id' => $permission->id]))->delete($return);
        } catch (Exception $e) {
            return Yii::$app->EC->callback($return, $e->getMessage());
        }

    }


    //查询角色所拥有权限
    public function getRolePermissions()
    {
        return array_map(function ($item) {
            return new AdminPermission(['id' => $item]);
        }, Yii::$app->RQ->AR(new AdminRolePermissionAR())->column([
            'where' => ['admin_role_id' => $this->id],
            'select' => ['admin_permission_id'],
        ]));
    }


}