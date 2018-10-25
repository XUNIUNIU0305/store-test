<?php
namespace custom\models\parts;

use Yii;
use common\models\parts\basic\UniqueIdGeneratorAbstract;
use common\ActiveRecord\OrderAR;

class OrderIdGenerator extends UniqueIdGeneratorAbstract{

    protected function getActiveRecord(){
        return new OrderAR;
    }

    protected function getFieldName(){
        return 'order_number';
    }
}
