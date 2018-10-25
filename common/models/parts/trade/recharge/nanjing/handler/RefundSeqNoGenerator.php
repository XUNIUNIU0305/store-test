<?php
namespace common\models\parts\trade\recharge\nanjing\handler;

use Yii;
use common\ActiveRecord\NanjingRefundAR;

class RefundSeqNoGenerator extends SeqNoGenerator{

    protected function getActiveRecord(){
        return new NanjingRefundAR;
    }

    protected function getFieldName(){
        return 'merchant_seq_no';
    }
}
