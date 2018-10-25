<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/16
 * Time: 10:13
 */

namespace admin\components\handler;


use admin\models\parts\role\AdminRole;
use common\ActiveRecord\AdminRoleAR;
use common\components\handler\Handler;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;

class AdminRoleHandler extends Handler
{

    //创建角色信息
    public static function create(string $name, string $introduction="intro",  $return = "throw")
    {
        try {
            //写入账户记录
            $id = Yii::$app->RQ->AR(new AdminRoleAR())->insert([
                'role_name' => $name,
                'role_introduction' => $introduction,
            ], $return);
            return new AdminRole(['id' => $id]);
        } catch (Exception $e) {

            return Yii::$app->EC->callback($return, $e->getMessage());
        }
    }

    //删除角色
    public static  function delete(AdminRole $role, $return = "throw")
    {
        return Yii::$app->RQ->AR(AdminRoleAR::findOne(['id' => $role->id]))->delete($return);
    }


    //获取账户列表
    public static function getRoleList($currentPage, $pageSize)
    {
        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 1;
        return new ActiveDataProvider([
            'query' => AdminRoleAR::find()->select([
                'id',
                'role_name',
                'role_introduction'
            ])->asArray(),
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