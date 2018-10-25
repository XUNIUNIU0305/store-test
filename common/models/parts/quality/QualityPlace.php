<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 18:04
 */

namespace common\models\parts\quality;


use common\ActiveRecord\QualityPackageAR;
use common\ActiveRecord\QualityPlaceAR;
use yii\base\InvalidCallException;
use yii\base\Object;

class QualityPlace extends  Object
{
    const TYPE_ALL=1;
    const TYPE_NORMAL=0;

    public $id;
    protected $AR;

    public function init(){
        if(!$this->AR=QualityPlaceAR::findOne($this->id))throw new InvalidCallException();
    }

    //获取部们名称
    public function getName(){
        return $this->AR->name;
    }

    //获取类型
    public function getType(){
        return $this->AR->type;
    }





}
