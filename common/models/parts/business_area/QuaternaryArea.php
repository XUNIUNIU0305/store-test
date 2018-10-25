<?php
namespace common\models\parts\business_area;

use Yii;
use yii\base\InvalidConfigException;
use common\ActiveRecord\BusinessQuaternaryAreaAR;
use common\ActiveRecord\BusinessAreaLeaderAR;

class QuaternaryArea extends TertiaryArea{

    //public $topId;
    //public $secondaryId;
    //public $tertiaryId;
    public $quaternaryId;

    //protected $AR;
    protected $_tertiaryId;

    public function init(){
        if(!is_null($this->quaternaryId)){
            if(!$this->AR = BusinessQuaternaryAreaAR::findOne($this->quaternaryId))throw new InvalidConfigException;
            $this->_tertiaryId = $this->AR->business_tertiary_area_id;
        }else{
            $this->_tertiaryId = $this->tertiaryId;
        }
    }


    public function getList(){
        if(!$this->_tertiaryId)return false;
        return Yii::$app->RQ->AR(new BusinessQuaternaryAreaAR)->all([
            'select' => ['id', 'title'],
            'where' => ['business_tertiary_area_id' => $this->_tertiaryId],
            'orderBy' => ['sort' => SORT_DESC],
        ]);
    }

    public function getChild(){
        return false;
    }

    public function hasChild(){
        return false;
    }

    public function getParent(){
        if(!$this->_tertiaryId)return false;
        return new TertiaryArea(['tertiaryId' => $this->_tertiaryId]);
    }

    public function getTertiaryArea(){
        if(is_null($this->AR))return false;
        return new TertiaryArea(['tertiaryId' => $this->AR->business_tertiary_area_id]);
    }

    public function getCommissarName(){
        if(is_null($this->AR))return false;
        return BusinessAreaLeaderAR::findOne($this->AR->commissar)->name;
    }
}
