<?php
namespace common\models\parts\trade\recharge\nanjing\message;

use Yii;
use common\models\parts\trade\recharge\nanjing\Nanjing;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicket;

class FailureOfDraw extends BaseAbstract{

    public $callbackPlain;
    public $drawTicketId;

    public function runExtra() : bool{
        $drawTicket = new DrawTicket(['id' => $this->drawTicketId]);
        $wallet = $drawTicket->userAccount->wallet;
        $wallet->thaw($drawTicket);
        $drawTicket->status = DrawTicket::STATUS_FAILURE;
        return true;
    }
}
