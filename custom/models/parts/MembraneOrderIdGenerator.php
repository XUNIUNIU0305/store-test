<?php
namespace custom\models\parts;

use Yii;
use common\models\parts\basic\UniqueIdGeneratorAbstract;
use common\ActiveRecord\MembraneOrderAR;

class MembraneOrderIdGenerator extends UniqueIdGeneratorAbstract{

    protected function getActiveRecord(){
        return new MembraneOrderAR;
    }

    protected function getFieldName(){
        return 'order_number';
    }
}
