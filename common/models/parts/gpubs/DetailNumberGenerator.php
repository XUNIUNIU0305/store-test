<?php
namespace common\models\parts\gpubs;

use Yii;
use common\models\parts\basic\UniqueIdGeneratorAbstract;

class DetailNumberGenerator extends UniqueIdGeneratorAbstract{

    public $length = 12;

    protected function getActiveRecord(){
        return new \common\ActiveRecord\ActivityGpubsGroupDetailAR;
    }

    protected function getFieldName(){
        return 'detail_number';
    }
}
