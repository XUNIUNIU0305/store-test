<?php
namespace common\models\parts\business_area;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;
use common\ActiveRecord\BusinessAreaLeaderAR;

class AreaLeader extends Object{

    public $id;

    protected $AR;

    public function init(){
        if(!$this->id || (!$this->AR = BusinessAreaLeaderAR::findOne($this->id)))throw new InvalidConfigException;
    }

    public function getName(){
        return $this->AR->name;
    }
    //获取电话号码
    public function getMobile(){
        return $this->AR->mobile;
    }
}
