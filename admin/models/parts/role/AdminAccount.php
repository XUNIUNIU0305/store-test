<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/15
 * Time: 16:54
 */

namespace admin\models\parts\role;


use common\ActiveRecord\AdminPermissionAR;
use common\ActiveRecord\AdminRolePermissionAR;
use common\ActiveRecord\AdminUserAR;
use common\ActiveRecord\AdminUserRoleAR;
use yii\base\Exception;
use yii\base\InvalidCallException;
use yii\base\Object;
use Yii;

class AdminAccount extends Object
{

    //账户状态
    CONST ACCOUNT_STATUS_PAUSE = 0;//停用
    CONST ACCOUNT_STATUS_START = 1;//可用

    public $id;
    protected $AR;

    /*初始化*/
    public function init()
    {
        if (!$this->id || !$this->AR = AdminUserAR::findOne($this->id)) throw new InvalidCallException();
    }


    /*设置密码*/
    public function setPassword($password, $return = "throw")
    {
        return Yii::$app->RQ->AR($this->AR)->update(['passwd' => Yii::$app->security->generatePasswordHash($password)], $return);
    }


    //更新用户属性信息
    public function setUserInfo($userInfo = [], $return = "throw")
    {

        return Yii::$app->RQ->AR($this->AR)->update($userInfo, $return);

    }


    //获取用户账户
    public function getAccount()
    {
        return $this->AR->account;
    }

    //获取用户姓名
    public function getName()
    {
        return $this->AR->name;
    }

    //获取手机号码
    public function getMobile()
    {
        return $this->AR->mobile;
    }

    //获取邮件地址
    public function getEmail()
    {
        return $this->AR->email;
    }


    //获取账户状态
    public function getStatus()
    {
        return $this->AR->status == self::ACCOUNT_STATUS_PAUSE ? false : true;
    }

    //设置账户状态
    public function setStatus($status, $return = "throw")
    {

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $data["status"] = $status;
            if ($status == self::ACCOUNT_STATUS_PAUSE) {
                //如果用户删除，置期部门为0
                $data["admin_department_id"] = 0;
                //解绑所有角色
                $this->revokeAllRoles($return);
            }
            //更新数据
            if (Yii::$app->RQ->AR($this->AR)->update($data, $return) !== false) {
                $transaction->commit();
                return true;
            }
            $transaction->rollBack();
            return false;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }

    }

    //查询用户所属部门
    public function getDepartment()
    {
        if ($this->AR->admin_department_id > 0) {
            return new AdminDepartment(['id' => $this->AR->admin_department_id]);
        }
        return false;
    }

    //设置用户所属部门
    public function setDepartment(AdminDepartment $department, $return = 'throw')
    {
        return Yii::$app->RQ->AR($this->AR)->update(['admin_department_id' => $department->id], $return);
    }

    //获取佣有角色
    public function getRoles($return = "throw")
    {
        return array_map(function ($item) {
            return new AdminRole(['id' => $item]);
        },
            Yii::$app->RQ->AR(new AdminUserRoleAR())->column([
                'where' => ['admin_user_id' => $this->id],
                'select' => ['admin_role_id'],
            ], $return)
        );
    }

    //用户绑定角色
    public function bindRole(AdminRole $role, $return = "throw")
    {
        return Yii::$app->RQ->AR(new AdminUserRoleAR())->insert(
            [
                'admin_user_id' => $this->id,
                'admin_role_id' => $role->id,
            ], $return
        );
    }

    //解绑所有角色
    public function revokeAllRoles($return = 'throw')
    {
        return AdminUserRoleAR::deleteAll(['admin_user_id'=>$this->id]);
    }

    //解除绑定
    public function revokeRole(AdminRole $role, $return = "throw")
    {
        return Yii::$app->RQ->AR(AdminUserRoleAR::findOne(['admin_user_id' => $this->id, 'admin_role_id' => $role->id]))->delete($return);
    }


    //获取用户菜
    public function getUserMenus($parentId = 0)
    {
        $roles = $this->getRoles();
        $topMenu = [];
        $menuIds = [];
        //取用户角色所拥有的权限
        foreach ($roles as $role) {
            foreach ($role->getRolePermissions() as $key) {
                if ($key->isMenu()) {
                    $parent = $key->getParent();
                    if ($parentId == 0 && !$parent) {
                        //取顶层父级菜单
                        if (!in_array($key->id, $menuIds)) {
                            $menuIds[] = $key->id;
                            $topMenu[] = $key;
                        }
                    } elseif ($parentId > 0) {
                        //取二级菜单
                        if ($parent && $parent->id == $parentId) {
                            if (!in_array($key->id, $menuIds)) {
                                $menuIds[] = $key->id;
                                $topMenu[] = $key;
                            }
                        }
                    }
                }
            }
        }
        return $topMenu;
    }

    //判断当前用户在当前方法下是否存在权限
    public function isExistsPermission($module, $controllerName, $actionName)
    {
        $where = "`" . AdminUserRoleAR::getTableSchema()->fullName . "`.`admin_user_id`=$this->id";
        $where .= " and module_name='$module' and controller_name='$controllerName' and action_name='$actionName'";
        return AdminPermissionAR::find()
            ->leftJoin('`' . AdminRolePermissionAR::getTableSchema()->fullName . "` on `" . AdminRolePermissionAR::getTableSchema()->fullName . "`.`admin_permission_id`=`" . AdminPermissionAR::getTableSchema()->fullName . "`.`id`")
            ->leftJoin("`" . AdminUserRoleAR::getTableSchema()->fullName . "` on `" . AdminUserRoleAR::getTableSchema()->fullName . "`.`admin_role_id`=`" . AdminRolePermissionAR::getTableSchema()->fullName . "`.`admin_role_id`")
            ->where($where)->exists();
    }


}
