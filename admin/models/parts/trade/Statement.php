<?php
namespace admin\models\parts\trade;

use Yii;
use common\models\parts\trade\StatementAbstract;
use common\ActiveRecord\AdminStatementAR;
use yii\base\InvalidCallException;

class Statement extends StatementAbstract{

    public $id;

    protected $AR;

    public function init(){
        if(!$this->id ||
            !$this->AR = AdminStatementAR::findOne($this->id))throw new InvalidCallException;
    }

    public function getAlterationType(){
        return $this->AR->alteration_type;
    }

    public function getLogId(){
        return $this->AR->corresponding_log_id;
    }

    public function getAlterationAmount(){
        return (float)$this->AR->alteration_amount;
    }

    public function getRMBBefore(){
        return (float)$this->AR->rmb_before;
    }

    public function getRMBAfter(){
        return (float)$this->AR->rmb_after;
    }

    public function getAlterationTime($unixTime = false){
        return $unixTime ? $this->AR->alteration_unixtime : $this->AR->alteration_datetime;
    }

    public function getWalletId(){
        return $this->AR->admin_wallet_id;
    }
}
