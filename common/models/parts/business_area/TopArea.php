<?php
namespace common\models\parts\business_area;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;
use common\ActiveRecord\BusinessTopAreaAR;
use common\ActiveRecord\BusinessSecondaryAreaAR;

class TopArea extends Object{

    public $topId;

    protected $AR;

    public function init(){
        if(!is_null($this->topId)){
            if(!$this->AR = BusinessTopAreaAR::findOne($this->topId))throw new InvalidConfigException;
        }
    }

    public function getTitle(){
        return is_null($this->AR) ? false : $this->AR->title;
    }

    public function getList(){
        return Yii::$app->RQ->AR(new BusinessTopAreaAR)->all([
            'select' => ['id', 'title'],
            'orderBy' => ['sort' => SORT_DESC],
        ]);
    }

    public function getChild(){
        if(is_null($this->AR))return false;
        return new SecondaryArea(['topId' => $this->AR->id]);
    }

    public function hasChild(){
        if(is_null($this->AR))return false;
        return Yii::$app->RQ->AR(new BusinessSecondaryAreaAR)->exists([
            'select' => ['id'],
            'where' => ['business_top_area_id' => $this->AR->id],
            'limit' => 1,
        ]);
    }
}
