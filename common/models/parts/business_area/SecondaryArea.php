<?php
namespace common\models\parts\business_area;

use Yii;
use yii\base\InvalidConfigException;
use common\ActiveRecord\BusinessSecondaryAreaAR;
use common\ActiveRecord\BusinessTertiaryAreaAR;

class SecondaryArea extends TopArea{

    //public $topId;
    public $secondaryId;

    //protected $AR;
    protected $_topId;

    public function init(){
        if(!is_null($this->secondaryId)){
            if(!$this->AR = BusinessSecondaryAreaAR::findOne($this->secondaryId))throw new InvalidConfigException;
            $this->_topId = $this->AR->business_top_area_id;
        }else{
            $this->_topId = $this->topId;
        }
    }

    public function getList(){
        if(!$this->_topId)return false;
        return Yii::$app->RQ->AR(new BusinessSecondaryAreaAR)->all([
            'select' => ['id', 'title'],
            'where' => ['business_top_area_id' => $this->_topId],
            'orderBy' => ['sort' => SORT_DESC],
        ]);
    }


    public function getChild(){
        if(is_null($this->AR))return false;
        return new TertiaryArea(['secondaryId' => $this->AR->id]);
    }

    public function hasChild(){
        if(is_null($this->AR))return false;
        return Yii::$app->RQ->AR(new BusinessTertiaryAreaAR)->exists([
            'select' => ['id'],
            'where' => ['business_secondary_area_id' => $this->AR->id],
            'limit' => 1,
        ]);
    }

    public function getParent(){
        if(!$this->_topId)return false;
        return new TopArea(['topId' => $this->_topId]);
    }

    public function getTopArea(){
        if(is_null($this->AR))return false;
        return new TopArea(['topId' => $this->AR->business_top_area_id]);
    }



}
