<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/16
 * Time: 14:44
 */

namespace admin\modules\site\models;


use admin\components\handler\AdminPermissionHandler;
use admin\components\handler\AdminRoleHandler;
use admin\models\parts\role\AdminAccount;
use admin\models\parts\role\AdminPermission;
use admin\models\parts\role\AdminRole;
use common\models\Model;
use yii\base\Exception;
use Yii;

class AdminroleModel extends Model
{


    public $id;
    public $role_name;
    public $role_introduction = "intro";

    public $is_menu;
    public $parent;


    public $permission_id;
    public $current_page;
    public $page_size;

    const SCE_ADD_ROLE = "add_role";//添加角色
    const SCE_MODIFY_ROLE = "modify_role";//编辑角色
    const SCE_DEL_ROLE = "del_role";//删除角色
    const SCE_GET_ROLE_LIST = "get_role_list";//获取角色列表
    const SCE_GET_PERMISSION_LIST = "get_permission_list";//获取权限列表
    const SCE_BIND_PERMISSION = "bind_permission";//绑定权限
    const SCE_REVOKE_PERMISSION = "revoke_permission";//解除绑定
    const SCE_GET_ROLE_PERMISSION = 'get_role_permission';//获取角色对应权限列表
    const SCE_GET_ROLE_BIND_USER = "get_role_bind_user";//获取角色绑定过的权限


    //设置场景
    public function scenarios()
    {
        return [
            self::SCE_ADD_ROLE => ['role_name'],
            self::SCE_MODIFY_ROLE => ['id', 'role_name'],
            self::SCE_DEL_ROLE => ['id'],
            self::SCE_GET_ROLE_LIST => ['current_page', 'page_size'],
            self::SCE_GET_PERMISSION_LIST => ['is_menu', 'parent', 'page_size', 'current_page'],
            self::SCE_BIND_PERMISSION => ['id', 'permission_id'],
            self::SCE_REVOKE_PERMISSION => ['id', 'permission_id'],
            self::SCE_GET_ROLE_PERMISSION => ['id'],
            self::SCE_GET_ROLE_BIND_USER => ['id'],
        ];
    }


    //设置规则
    public function rules()
    {
        return [
            [
                ['role_name'],
                'common\validators\Admin\AdminRoleVaildator',
                'id' => $this->id,
                'message' => 5162,
                'on' => [self::SCE_ADD_ROLE, self::SCE_MODIFY_ROLE],
            ],
            [
                ['id'],
                'common\validators\Admin\AdminRoleVaildator',
                'message' => 5163,//验证角色不存在时返回
                'rolePermissionExists' => 5165,//绑定时，记录已存在返回代码
                'rolePermissionNotExists' => 5166,//取消绑定时，记录不存在返回代码
                'permissionId' => $this->permission_id,
                'scenarios' => $this->getScenario(),
                'on' => [self::SCE_MODIFY_ROLE, self::SCE_DEL_ROLE, self::SCE_BIND_PERMISSION, self::SCE_REVOKE_PERMISSION, self::SCE_GET_ROLE_PERMISSION],
            ],
            [
                ['permission_id'],
                'common\validators\Admin\AdminPermissionVaildator',
                'message' => 5164,
                'on' => [self::SCE_BIND_PERMISSION, self::SCE_REVOKE_PERMISSION],
            ],
            [
                ['role_name'],
                'required',
                'message' => 9001
            ],

            [
                ['current_page'],
                'default',
                'value' => 1,
            ],
            [
                ['page_size'],
                'default',
                'value' => 1000,
            ],
            [
                ['is_menu'],
                'default',
                'value' => null,
            ],
            [
                ['parent'],
                'default',
                'value' => null,
            ],
            [
                ['permission_id'],
                'required',
                'message' => 9001
            ],
            [
                ['current_page', 'page_size', 'id', 'permission_id', 'parent'],
                'number',
                'integerOnly' => true,
                'message' => 9001,
            ],
            [
                ['is_menu'],
                'in',
                'range' => [0, 1],
                'message' => 9001,
            ],

        ];
    }

    public function getRoleBindUser()
    {
        $users = (new AdminRole(['id' => $this->id]))->getBindUserList();
        return array_map(function ($item) {
            return ['id' => $item->id, 'account' => $item->getAccount(), 'name' => $item->getName()];
        }, $users);


    }

    //获取角色对应权限
    public function getRolePermission()
    {
        $role = new AdminRole(['id' => $this->id]);
        $list = $role->getRolePermissions();
        return array_map(function (AdminPermission $item) {
            return [
                'id' => $item->id,
                'name' => $item->getName(),
                'module_name' => $item->getModulename(),
                'controller_name' => $item->getControllerName(),
                'action_name' => $item->getActionName(),
                'parent' => $item->getParent(),
                'is_menu' => $item->isMenu(),
            ];
        }, $list);
    }

    //角色绑定
    public function revokePermission()
    {
        try {
            $permission = new AdminPermission(['id' => $this->permission_id]);
            $role = new AdminRole(['id' => $this->id]);
            if ($role->revokePermission($permission)) {
                return true;
            }
            $this->addError('revokePermission', 5159);
            return false;
        } catch (Exception $e) {
            $this->addError('revokePermission', 5159);
            return false;
        }
    }


    //绑定权限
    public function bindPermission()
    {
        try {
            $permission = new AdminPermission(['id' => $this->permission_id]);
            $role = new AdminRole(['id' => $this->id]);
            if ($role->bindPermission($permission)) {
                return true;
            }
            $this->addError('bindPermission', 5158);
            return false;
        } catch (Exception $e) {
            $this->addError('bindPermission', 5158);
            return false;
        }
    }

    //获取权限列表
    public function getPermissionList()
    {

        $permissionParent = AdminPermissionHandler::getPermissionList(1, 1000, 1, -1);
        return array_map(function ($item) {
            $item['sub'] = AdminPermissionHandler::getPermissionList(1, 1000, 0, $item['id'])->models;
            return $item;

        }, $permissionParent->models);


    }


    //编辑角色信息
    public function modifyRole()
    {
        $role = new AdminRole(['id' => $this->id]);
        if (false !== $role->setRoleInformation(['role_name' => $this->role_name, 'role_introduction' => $this->role_introduction], false)) {
            return true;
        }
        $this->addError('modifyRole', 5157);
        return false;
    }

    //删除角色
    public function delRole()
    {
        $role = new AdminRole(['id' => $this->id]);
        if ($role->hasBindUser() || $role->hasBindPermission()) {
            $this->addError('delRole', 5172);
            return false;
        }
        if (AdminRoleHandler::delete($role)) {
            return true;
        }
        $this->addError('delRole', 5167);
        return false;
    }

    //添加角色
    public function addRole()
    {
        if (AdminRoleHandler::create($this->role_name, $this->role_introduction)) {
            return true;
        }
        $this->addError('addRole', 5156);
        return false;
    }


    //查询获取角色列表
    public function getRoleList()
    {
        $roles = AdminRoleHandler::getRoleList($this->current_page, $this->page_size);
        return [
            'count' => $roles->count,
            'total_count' => $roles->totalCount,
            'codes' => $roles->models,
        ];
    }

}