<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/16
 * Time: 11:38
 */

namespace admin\components\handler;


use admin\models\parts\role\AdminDepartment;
use common\ActiveRecord\AdminDepartmentAR;
use common\components\handler\Handler;
use Yii;
use yii\data\ActiveDataProvider;

class AdminDepartmentHandler extends Handler
{

    //创建部门
    public static function create(string $name, string $introduction, $return = "throw")
    {

        $id = Yii::$app->RQ->AR(new AdminDepartmentAR())->insert([
            'name' => $name,
            'introduction' => $introduction
        ], $return);

        return new AdminDepartment(['id' => $id])??Yii::$app->EC->callback($return, '创建部门信息失败');
    }

    //删除部门
    public static function delete(AdminDepartment $department, string $return = "throw")
    {
        //检测是否存在用户
        if ($department->hasEmployee()) {
            return Yii::$app->EC->callback($return, "该部分下存在员工，暂时不允许删除!");
        }
        return Yii::$app->RQ->AR(AdminDepartmentAR::findOne(['id' => $department->id]))->delete($return);

    }


    //获取部门列表
    public static function getDepartmentList($currentPage, $pageSize)
    {
        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 1;
        return new ActiveDataProvider([
            'query' => AdminDepartmentAR::find()->select([
                'id',
                'name',
                'introduction'
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