<?php
namespace supply\models\parts\trade;

use Yii;
use common\models\parts\trade\StatementAbstract;
use common\ActiveRecord\SupplyUserStatementAR;
use yii\base\InvalidCallException;

class Statement extends StatementAbstract{

    public $id;

    protected $AR;

    public function init(){
        if(!$this->id ||
            (!$this->AR = SupplyUserStatementAR::findOne($this->id)))throw new InvalidCallException;
    }

    public function getAlterationType(){
        return $this->AR->alteration_type;
    }

    public function getLogId(){
        return $this->AR->corresponding_log_id;
    }

    public function getAlterationAmount(){
        return $this->AR->alteration_amount;
    }

    public function getRMBBefore(){
        return $this->AR->rmb_before;
    }

    public function getRMBAfter(){
        return $this->AR->rmb_after;
    }

    public function getAlterationTime($unixTime = false){
        return $unixTime ? $this->AR->alteration_unixtime : $this->AR->alteration_datetime;
    }
}
