<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/16
 * Time: 14:44
 */

namespace admin\modules\site\models;


use admin\components\AdminUser;
use admin\components\handler\AdminAccountHandler;
use admin\models\parts\role\AdminAccount;
use admin\models\parts\role\AdminDepartment;
use admin\models\parts\role\AdminRole;
use common\ActiveRecord\AdminUserAR;
use common\models\Model;
use yii\base\Exception;
use Yii;

class AdminuserModel extends Model
{


    public $id;
    public $account;
    public $password;
    public $confirm_password;
    public $mobile;
    public $name;
    public $email;
    public $department_id;
    public $keyword;
    public $status;
    public $role_id;

    public $current_page;
    public $page_size;

    const SCE_ADD_USER = "add_user";//添加用户
    const SCE_MODIFY_USER = "modify_user";//编辑用户
    const SCE_GET_USER_LIST = "get_user_list";//获取用户列表
    const SCE_GET_USER_ROLES = "get_user_roles";//获取用户角色列表
    const SCE_DELETE_USER = "delete_user";//删除用户


    //设置场景
    public function scenarios()
    {

        return [
            self::SCE_ADD_USER => ['password', 'name', 'mobile', 'email', 'department_id', 'status', 'role_id', 'confirm_password'],
            self::SCE_GET_USER_LIST => ['current_page', 'page_size', 'keyword', 'department_id', 'role_id'],
            self::SCE_MODIFY_USER => ['id', 'password', 'name', 'mobile', 'email', 'department_id', 'status', 'role_id', 'confirm_password'],
            self::SCE_GET_USER_ROLES => ['id'],
            self::SCE_DELETE_USER => ['id'],

        ];
    }


    //设置规则
    public function rules()
    {
        return [
            [
                ['name', 'mobile', 'email', 'status'],
                'required',
                'message' => 9001
            ],

            [
                ['id'],
                'exist',
                'targetClass' => AdminUserAR::className(),
                'targetAttribute' => 'id',
                'message' => 5170,
            ],
            [
                ['mobile'],
                'integer',
                'min' => 10000000000,
                'max' => 19999999999,
                'tooSmall' => 3166,
                'tooBig' => 3166,
                'message' => 3166,
            ],
            [//添加用户时，验证密码为必填
                ['password'],
                'string',
                'length' => [8, 100],
                'tooShort' => 3162,
                'tooLong' => 3162,
                'message' => 3162,
            ],
            [
                ['email'],
                'email',
                'message' => 5169,
            ],
            [
                //验证密码是否相同
                ['confirm_password'],
                'required',
                'requiredValue' => $this->password,
                'message' => 5168,//两次填写密码不相同
                'on' => [self::SCE_ADD_USER, self::SCE_MODIFY_USER],
            ],
            [
                ['department_id'],
                'default',
                'value' => 0
            ],
            [
                ['role_id'],
                'default',
                'value' => [],
                'on' => [self::SCE_ADD_USER, self::SCE_MODIFY_USER]
            ],
            [
                ['role_id'],
                'default',
                'value' => 0,
                'on' => [self::SCE_GET_USER_LIST]
            ],


            [
                ['current_page', 'page_size', 'id'],
                'number',
                'integerOnly' => true,
                'message' => 9001,
            ],
            [
                ['department_id'],
                'number',
                'integerOnly' => true,
                'message' => 9001,
            ],
            [
                ['status'],
                'in',
                'range' => [0, 1],
                'message' => 9001,
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
            ]

        ];
    }


    //删除用户信息
    public function deleteUser()
    {

        if ((new AdminAccount(['id' => $this->id]))->setStatus(0, false) !== false) {
            return true;
        }
        $this->addError('deleteUser', 5171);
        return false;
    }


    //获取用户角色
    public function getUserRoles()
    {

        return array_map(function (AdminRole $item) {
            return [
                'id' => $item->id,
                'role_name' => $item->getRoleName(),
                'role_introduction' => $item->getRoleIntroduction(),
            ];
        }, (new AdminAccount(['id' => $this->id]))->getRoles());

    }

    //编辑用户信息
    public function modifyUser()
    {
        //开启事务
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = new AdminAccount(['id' => $this->id]);

            //编辑用户基本信息
            if ($this->password) {
                if (!$user->setPassword($this->password,false)) {
                    $transaction->rollBack();
                    $this->addError('modifyUser', 5115);
                    return false;
                }
            }



            //保存用户基本信息
            if ($user->setUserInfo([
                'name' => $this->name,
                'mobile' => $this->mobile,
                'email' => $this->email,
                'status' => $this->status,
            ],false)===false
            ) {

                $transaction->rollBack();
                $this->addError('modifyUser', 5115);
                return false;
            }

            //设置用户部门
            $department = new AdminDepartment(['id' => $this->department_id]);
            if (false === $user->setDepartment($department, false)) {
                $transaction->rollBack();


                $this->addError('modifyUser', 5115);
                return false;
            }
            //设置用户角色
            //获取现有配置项
            $settingAlready = array_map(function ($item) {
                return $item->id;
            }, $user->getRoles());
            //获取删除项
            $deleteItems = array_diff($settingAlready, $this->role_id);
            foreach ($deleteItems as $key => $var) {
                $user->revokeRole(new AdminRole(['id' => $var]));
            }
            //获取增加项
            $addItems = array_diff($this->role_id, $settingAlready);
            foreach ($addItems as $key => $var) {
                $user->bindRole(new AdminRole(['id' => $var]));
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {

            $this->addError("modifyUser", 5115);
            //回滚事务
            $transaction->rollBack();
            return false;
        }
    }


    //添加用户
    public function addUser()
    {
        try {
            //创建部门对象
            $department = new AdminDepartment(['id' => $this->department_id]);
            //创建角色对象
            $roles = [];
            foreach ($this->role_id as $role_id) {
                $roles[] = new AdminRole(['id' => $role_id]);
            }
            if (AdminAccountHandler::create($this->name, $this->password, $this->mobile, $this->email, $department, $roles)) {
                return true;
            }
            $this->addError('addUser', 5114);
            return false;
        } catch (Exception $e) {
            $this->addError("addUser", 5114);
            return false;
        }
    }


    //获取用户列表
    public function getUserList()
    {
        $user = AdminAccountHandler::getAccountList($this->current_page, $this->page_size, $this->department_id, $this->role_id, $this->keyword);

        //添加输出部门信息
        $code = array_map(function ($item) {
            //获取部门
            $item['department_name'] = (new AdminDepartment(['id' => $item["admin_department_id"]]))->getName();
            //获取角色
            $item["roles_name"] = array_map(function ($role) {
                return ['name' => $role->getRoleName(), 'id' => $role->id];
            }, (new AdminAccount(['id' => $item["id"]]))->getRoles());
            return $item;
        }, $user->models);

        return [
            'count' => $user->count,
            'total_count' => $user->totalCount,
            'codes' => $code,
        ];
    }

}