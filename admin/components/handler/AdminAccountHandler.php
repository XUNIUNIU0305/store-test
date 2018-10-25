<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/16
 * Time: 10:13
 */

namespace admin\components\handler;


use admin\models\parts\role\AdminAccount;
use admin\models\parts\role\AdminDepartment;
use common\ActiveRecord\AdminUserAR;
use common\ActiveRecord\AdminUserRoleAR;
use common\components\handler\Handler;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;

class AdminAccountHandler extends Handler
{

    //创建用户账户信息
    public static function create(string $name, string $password, string $mobile, string $email, AdminDepartment $department, array $roles = [], $return = "throw")
    {
        //启动事务
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //创建账户
            $account = self::createAccount();
            //写入账户记录
            $id = Yii::$app->RQ->AR(new AdminUserAR())->insert([
                'account' => $account,
                'passwd' => Yii::$app->security->generatePasswordHash($password),
                'mobile' => $mobile,
                'name' => $name,
                'email' => $email,
                'admin_department_id' => $department->id,
            ], $return);
            //检测是否需要写入
            $user = new AdminAccount(['id' => $id]);
            //检测写入配置角色
            foreach ($roles as $role) {
                $user->bindRole($role);
            }
            $transaction->commit();
            return $user;
        } catch (Exception $e) {
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e->getMessage());
        }
    }


    //创建生成用户名
    private static function createAccount()
    {
        $account = rand(100000000, 999999999);
        if (AdminUserAR::findOne(['account' => $account])) {
            return self::createAccount();
        }
        return $account;
    }


    //获取账户列表
    public static function getAccountList($currentPage, $pageSize, $departmentId = 0, $roleId = 0, $keyword = '')
    {
        $where = [];
        //条件
        if ($departmentId > 0) {
            $where['admin_department_id'] = ['admin_department_id' => $departmentId];
        }
        //关键字
        if ($keyword != "") {
            $where['name'] = [
                'like', 'name', $keyword
            ];
        }

        if ($roleId > 0) {
            $adminIds = Yii::$app->RQ->AR(new AdminUserRoleAR())
                ->column(
                    [
                        'where' => ['admin_role_id' => $roleId],
                        'select' => ['admin_user_id'],
                        'groupby' => ['admin_user_id'],
                    ]
                );
            $where['id'] = $adminIds;
        }

        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 1;
        return new ActiveDataProvider([
            'query' => AdminUserAR::find()->select([
                'id',
                'account',
                'passwd',
                'mobile',
                'name',
                'email',
                'admin_department_id',
                'status'
            ])->where($where)->andWhere("status='1'")->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);
    }


}