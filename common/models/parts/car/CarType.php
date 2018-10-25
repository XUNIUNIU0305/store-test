<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 11:33
 */

namespace common\models\parts\car;



use common\ActiveRecord\CarTypeAR;
use common\models\parts\car\CarBrand;
use yii\base\InvalidCallException;
use yii\base\Object;

class CarType extends  Object
{

    public $id;

    protected $AR;


    public function init(){
        if(!$this->id||!$this->AR=CarTypeAR::findOne($this->id))throw new InvalidCallException();
    }


    //获取类型名称
    public function getName(){
        return $this->AR->name;
    }

    //获取所属品牌
    public function getBrand(){
        return new CarBrand(['id'=>$this->AR->brand_id]);
    }

    //获取对应字母表
    public function getChar(){
        return (new CarAlphabet(['id'=>$this->AR->alphabet_id]))->getName();
    }


}