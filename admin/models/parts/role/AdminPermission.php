<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/15
 * Time: 16:54
 */

namespace admin\models\parts\role;


use common\ActiveRecord\AdminPermissionAR;
use yii\base\InvalidCallException;
use yii\base\Object;

class AdminPermission extends Object
{

    CONST IS_MENU=1;
    CONST IS_NOT_MENU=0;



    public $id;
    protected $AR;


    public function init()
    {
        if (!$this->id || !$this->AR = AdminPermissionAR::findOne(($this->id))) throw new InvalidCallException();
    }


    //获取名称
    public function getName()
    {
        return $this->AR->name;
    }

    //获取模块名称
    public function getModuleName()
    {
        return $this->AR->module_name;
    }

    //获取控制器名称
    public function getControllerName()
    {
        return $this->AR->controller_name;
    }

    //获取父级
    public function getParent()
    {
        if ($this->AR->parent > 0) {
            return new AdminPermission(['id' => $this->AR->parent]);
        } else {
            return false;
        }
    }

    //是否为菜单
    public function isMenu()
    {
        return $this->AR->is_menu == 0 ? false : true;
    }

    //获取控制器名称
    public function getActionName()
    {
        return $this->AR->action_name;
    }

    //设置是否为父级
    public function isParent()
    {
        return $this->AR->parent > 0 ? true : false;
    }


}