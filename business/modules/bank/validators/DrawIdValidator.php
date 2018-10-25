<?php
namespace business\modules\bank\validators;

use Yii;
use common\models\Validator;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicket;

class DrawIdValidator extends Validator{

    public $id;
    public $userId;
    public $message;

    protected function validateValue($drawId){
        try{
            $drawTicket = new DrawTicket([
                'id' => $drawId,
            ]);
        }catch(\Exception $e){
            return $this->message;
        }
        if($this->userId){
            if($drawTicket->userAccount->id == $this->userId){
                return true;
            }else{
                return $this->message;
            }
        }
    }
}
