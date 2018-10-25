<?php
namespace common\models\parts\trade\recharge;

use common\models\parts\basic\UniqueIdGeneratorAbstract;
use common\ActiveRecord\RechargeApplyAR;

class RechargeIdGenerator extends UniqueIdGeneratorAbstract{

    protected function getActiveRecord(){
        return new RechargeApplyAR;
    }

    protected function getFieldName(){
        return 'recharge_number';
    }
}
