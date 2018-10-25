<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 11:33
 */

namespace common\models\parts\car;


use common\ActiveRecord\CarAlphabetAR;
use yii\base\InvalidCallException;
use yii\base\Object;

class CarAlphabet extends  Object
{

    public $id;

    protected $AR;


    public function init(){
        if(!$this->id||!$this->AR=CarAlphabetAR::findOne($this->id))throw new InvalidCallException();
    }


    public function getName(){
        return $this->AR->name;
    }

    



}