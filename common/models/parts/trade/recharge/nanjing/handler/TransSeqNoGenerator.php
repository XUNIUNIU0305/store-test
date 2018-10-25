<?php
namespace common\models\parts\trade\recharge\nanjing\handler;

use Yii;
use common\ActiveRecord\NanjingPayBalanceAR;

class TransSeqNoGenerator extends SeqNoGenerator{

    public function init(){
        parent::init();
        $this->_verify = true;
    }

    protected function getActiveRecord(){
        return new NanjingPayBalanceAR;
    }

    protected function getFieldName(){
        return 'merchant_seq_no';
    }
}
