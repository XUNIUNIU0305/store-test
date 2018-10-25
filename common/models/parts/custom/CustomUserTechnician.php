<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 15:24
 */

namespace common\models\parts\custom;


use common\ActiveRecord\CustomUserTechnicianAR;
use yii\base\InvalidCallException;
use yii\base\Object;

class CustomUserTechnician extends Object
{

    public $id;
    protected $AR;

    public function init(){
        if(!$this->id||!$this->AR=CustomUTechnicianAR::findOne($this->id))throw new InvalidCallException();
    }


    //获取技师姓名
    public function getName(){
        return $this->AR->name;
    }

    //获取质量所属门店
    public function getCustomer(){
        return new CustomUser(['id'=>$this->AR->custom_user_id]);
    }

}