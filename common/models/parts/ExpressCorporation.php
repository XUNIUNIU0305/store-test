<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/5
 * Time: 15:52
 */

namespace common\models\parts;


use common\ActiveRecord\ExpressCorporationAR;
use yii\base\InvalidCallException;
use yii\base\Object;

class ExpressCorporation extends Object
{

    public $id;
    protected $AR;


    public function init(){
        if(!$this->id||!$this->AR=ExpressCorporationAR::findOne($this->id))throw new InvalidCallException();
    }

    public function getName(){
        return $this->AR->name;
    }

    //获取编码
    public function getCode(){
        return $this->AR->code;
    }

    /**
     * 快递公司列表
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getExpressItems()
    {
        $models = ExpressCorporationAR::find()
            ->select(['id', 'name', 'first_char'])
            ->orderBy('first_char asc')
            ->asArray()->all();

        return $models;
    }
}