<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 11:33
 */

namespace common\models\parts\car;


use common\ActiveRecord\CarBrandAR;
use common\components\handler\car\CarTypeHandler;
use common\models\parts\car\CarAlphabet;
use yii\base\InvalidCallException;
use yii\base\Object;

class CarBrand extends  Object
{

    public $id;

    protected $AR;


    public function init(){
        if(!$this->id||!$this->AR=CarBrandAR::findOne($this->id))throw new InvalidCallException();
    }




    //获取名称
    public function getName(){
        return $this->AR->name;
    }


    //获取对应字母表
    public function getChar(){
        return (new CarAlphabet(['id'=>$this->AR->alphabet_id]))->getName();
    }

    //获取下级类型列表
    public function getTypeList(){
        return array_map(function($item){
            return [
                'id'=>$item->id,
                'name'=>$item->getName(),
                'sign'=>$item->getChar(),
            ];
        },CarTypeHandler::getTypeList($this));
    }

    public function getLogo()
    {
        return $this->AR->logo_img;
    }



}