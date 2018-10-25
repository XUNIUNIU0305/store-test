<?php
namespace custom\models\parts;

use common\ActiveRecord\OrderRefundAR;
use Yii;
use common\models\parts\basic\UniqueIdGeneratorAbstract;
use common\ActiveRecord\OrderAR;

class RefundIdGenerator extends UniqueIdGeneratorAbstract{

    public $length=11;

    protected function getActiveRecord(){
        return new OrderRefundAR();
    }

    protected function getFieldName(){
        return 'code';
    }
}
