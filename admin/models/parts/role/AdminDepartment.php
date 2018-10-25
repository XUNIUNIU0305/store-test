<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/15
 * Time: 16:54
 */

namespace admin\models\parts\role;


use common\ActiveRecord\AdminUserAR;
use common\ActiveRecord\AdminDepartmentAR;
use yii\base\InvalidCallException;
use yii\base\Object;
use Yii;

class AdminDepartment extends Object
{


    public $id;
    protected $AR;

    public function init()
    {
        if (!$this->id || !$this->AR = AdminDepartmentAR::findOne($this->id)) throw new InvalidCallException();
    }


    //取名称
    public function getName()
    {
        return $this->AR->name;
    }


    //取简介
    public function getIntroduction()
    {
        return $this->AR->introduction;
    }

    //设置多属性
    public function setDepartmentInfo($data = [], $return = "throw")
    {
        return Yii::$app->RQ->AR($this->AR)->update($data, $return);
    }

    //设置名称
    public function setName($name, $return = "throw")
    {
        return Yii::$app->RQ->AR($this->AR)->update(['name' => $name], $return);
    }

    //设置简介
    public function setIntroduction($introduction, $return = "throw")
    {
        return Yii::$app->RQ->AR($this->AR)->update(['introduction' => $introduction], $return);
    }

    //检测部门下是否存在员工
    public function hasEmployee()
    {
        return AdminUserAR::find()->where(['admin_department_id' => $this->id])->exists() ? true : false;
    }

    //获取部门所有员工
    public function getEmployees()
    {
        return array_map(
            function ($item) {

                return new AdminAccount(['id' => $item]);
            }, Yii::$app->RQ->AR(new AdminUserAR())->column(
            [
                'select' => ['id'],
                'where' => ['admin_department_id' => $this->id,'status'=>AdminAccount::ACCOUNT_STATUS_START],
            ]
        )
        );
    }


}