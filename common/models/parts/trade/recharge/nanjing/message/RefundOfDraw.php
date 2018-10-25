<?php
namespace common\models\parts\trade\recharge\nanjing\message;

use Yii;

class RefundOfDraw extends BaseAbstract{

    public $callbackPlain;
    public $drawTicketId;
    public $payBalanceId;
    public $orgMerchantSeqNo;
    public $merchantDateTime;
    public $merchantSeqNo;

    public function runExtra() : bool{
    
    }
}
