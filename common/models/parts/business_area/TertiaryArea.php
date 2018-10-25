<?php
namespace common\models\parts\business_area;

use Yii;
use yii\base\InvalidConfigException;
use common\ActiveRecord\BusinessTertiaryAreaAR;
use common\ActiveRecord\BusinessQuaternaryAreaAR;

class TertiaryArea extends SecondaryArea{

    //public $topId;
    //public $secondaryId;
    public $tertiaryId;

    //protected $AR;
    protected $_secondaryId;

    public function init(){
        if(!is_null($this->tertiaryId)){
            if(!$this->AR = BusinessTertiaryAreaAR::findOne($this->tertiaryId))throw new InvalidConfigException;
            $this->_secondaryId = $this->AR->business_secondary_area_id;
        }else{
            $this->_secondaryId = $this->secondaryId;
        }
    }



    public function getList(){
        if(!$this->_secondaryId)return false;
        return Yii::$app->RQ->AR(new BusinessTertiaryAreaAR)->all([
            'select' => ['id', 'title'],
            'where' => ['business_secondary_area_id' => $this->_secondaryId],
            'orderBy' => ['sort' => SORT_DESC],
        ]);
    }

    public function getChild(){
        if(is_null($this->AR))return false;
        return new QuaternaryArea(['tertiaryId' => $this->AR->id]);
    }

    public function hasChild(){
        if(is_null($this->AR))return false;
        return Yii::$app->RQ->AR(new BusinessQuaternaryAreaAR)->exists([
            'select' => ['id'],
            'where' => ['business_tertiary_area_id' => $this->AR->id],
            'limit' => 1,
        ]);
    }

    public function getLeader(){
        if(is_null($this->AR))return false;
        return new AreaLeader(['id' => $this->AR->business_area_leader_id]);
    }

    public function getParent(){
        if(!$this->_secondaryId)return false;
        return new SecondaryArea(['secondaryId' => $this->_secondaryId]);
    }

    public function getSecondaryArea(){
        if(is_null($this->AR))return false;
        return new SecondaryArea(['secondaryId' => $this->AR->business_secondary_area_id]);
    }
}
