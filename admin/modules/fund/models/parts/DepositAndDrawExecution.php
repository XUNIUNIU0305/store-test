<?php
namespace admin\modules\fund\models\parts;

use Yii;
use yii\base\Object;
use common\ActiveRecord\NonTransactionDepositAndDrawAR;

class DepositAndDrawExecution extends Object{

    private $_delayTime = 60;

    public function init(){
        $delayTimeConfigFilePath = __DIR__ . '/DelayTimeConfig.php';
        if(is_file($delayTimeConfigFilePath)){
            try{
                /**
                 * return [
                 *     'delay_time' => integer
                 * ];
                 */
                $delayTime = include($delayTimeConfigFilePath);
                if(is_array($delayTime) &&
                    array_key_exists('delay_time', $delayTime) &&
                    is_int($delayTime['delay_time']) &&
                    $delayTime['delay_time'] >= 60
                ){
                    $this->_delayTime = $delayTime['delay_time'];
                }
            }catch(\Exception $e){
                $this->_delayTime = 60;
            }
        }
    }

    public function getTickets(){
        $ticketIds = Yii::$app->RQ->AR(new NonTransactionDepositAndDrawAR)->column([
            'select' => ['id'],
            'where' => [
                'status' => DepositAndDrawTicket::STATUS_AUTHORIZED,
            ],
            'andWhere' => [
                '<=', 'pass_unixtime', Yii::$app->time->unixTime - $this->_delayTime,
            ],
        ]);
        return array_map(function($ticketId){
            return new DepositAndDrawTicket([
                'id' => $ticketId,
            ]);
        }, $ticketIds);
    }

    public function execute(){
        if($tickets = $this->tickets){
            foreach($tickets as $ticket){
                $ticket->execute(false);
            }
        }
        return true;
    }
}
