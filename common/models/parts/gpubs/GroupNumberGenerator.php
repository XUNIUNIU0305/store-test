<?php
namespace common\models\parts\gpubs;

use Yii;
use common\models\parts\basic\UniqueIdGeneratorAbstract;

class GroupNumberGenerator extends UniqueIdGeneratorAbstract{

    public $length = 8;

    protected function getActiveRecord(){
        return new \common\ActiveRecord\ActivityGpubsGroupAR;
    }

    protected function getFieldName(){
        return 'group_number';
    }
}
