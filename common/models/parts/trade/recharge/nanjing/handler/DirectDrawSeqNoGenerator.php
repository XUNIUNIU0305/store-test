<?php
namespace common\models\parts\trade\recharge\nanjing\handler;

use Yii;
use common\ActiveRecord\NanjingDrawAR;

class DirectDrawSeqNoGenerator extends SeqNoGenerator{

    protected function getActiveRecord(){
        return new NanjingDrawAR;
    }

    protected function getFieldName(){
        return 'merchant_seq_no';
    }
}
