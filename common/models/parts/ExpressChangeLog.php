<?php
namespace common\models\parts;

use common\ActiveRecord\ExpressChangeLogAR;
use yii\base\Object;

class ExpressChangeLog extends Object{

    public $number;
    protected $AR;

    public function init(){
        $this->AR = ExpressChangeLogAR::findOne(['number'=>$this->number]);
    }

    public function setChangeLog($changeLog){
        $this->AR->change_log = $changeLog;
        return $this->AR->update();
    }


    public function setReason($reason){
        $this->AR->reason = $reason;
        return $this->AR->update();
    }
}
