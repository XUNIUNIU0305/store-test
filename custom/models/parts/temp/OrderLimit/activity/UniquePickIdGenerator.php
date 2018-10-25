<?php
namespace custom\models\parts\temp\OrderLimit\activity;

use Yii;
use common\models\parts\basic\UniqueIdGeneratorAbstract;
use common\ActiveRecord\CustomUserActivityLimitAR;

class UniquePickIdGenerator extends UniqueIdGeneratorAbstract{

    public $length = 12;

    protected function getActiveRecord(){
        return new CustomUserActivityLimitAR;
    }

    protected function getFieldName(){
        return 'pick_id';
    }
}
