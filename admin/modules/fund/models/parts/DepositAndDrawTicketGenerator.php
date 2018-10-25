<?php
namespace admin\modules\fund\models\parts;

use Yii;
use yii\base\Object;
use yii\base\InvalidParamException;
use yii\base\InvalidCallException;
use common\ActiveRecord\NonTransactionDepositAndDrawAR;
use admin\models\parts\role\AdminAccount;

class DepositAndDrawTicketGenerator extends Object{

    private $_operateUser;
    private $_targetUserId;
    private $_targetUserAccount;
    private $_targetUserType;
    private $_operateType;
    private $_amount;
    private $_operateBrief;
    private $_operateDetail;


    public function setOperateUser(AdminAccount $user){
        $this->_operateUser = $user;
        return $this;
    }

    public function setTargetUser($user){
        if($user instanceof \business\models\parts\Account){
            $this->_targetUserType = DepositAndDrawTicket::TARGET_USER_TYPE_BUSINESS;
        }elseif($user instanceof \common\models\parts\custom\CustomUser){
            $this->_targetUserType = DepositAndDrawTicket::TARGET_USER_TYPE_CUSTOM;
        }else{
            throw new InvalidParamException('unavailable user');
        }
        $this->_targetUserId = $user->id;
        $this->_targetUserAccount = $user->account;
        return $this;
    }

    public function setOperateType($type){
        if(!in_array($type, [
            DepositAndDrawTicket::OPERATE_TYPE_DEPOSIT,
            DepositAndDrawTicket::OPERATE_TYPE_DRAW,
        ]))throw new InvalidParamException('unavailable operate type');
        $this->_operateType = $type;
        return $this;
    }

    public function setAmount(float $amount){
        if($amount <= 0)throw new InvalidParamException('unavailable operate amount');
        $this->_amount = $amount;
        return $this;
    }

    public function setOperateBrief(string $brief){
        if(!$brief)throw new InvalidParamException('need non-empty brief');
        $this->_operateBrief = $brief;
        return $this;
    }

    public function setOperateDetail(string $detail){
        if(!$detail)throw new InvalidParamException('need non-empty detail');
        $this->_operateDetail = $detail;
        return $this;
    }

    public function generate($return = 'throw'){
        foreach([
            '_operateUser',
            '_targetUserId',
            '_targetUserAccount',
            '_targetUserType',
            '_operateType',
            '_amount',
            '_operateBrief',
            '_operateDetail',
        ] as $variable){
            if(is_null($this->$variable))throw new InvalidCallException('missing param');
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $ticketId = Yii::$app->RQ->AR(new NonTransactionDepositAndDrawAR)->insert([
                'operate_type' => $this->_operateType,
                'user_type' => $this->_targetUserType,
                'user_id' => $this->_targetUserId,
                'user_account' => $this->_targetUserAccount,
                'amount' => $this->_amount,
                'operate_brief' => $this->_operateBrief,
                'operate_detail' => $this->_operateDetail,
                'cancel_reason' => '',
                'status' => DepositAndDrawTicket::STATUS_UNAUTHORIZED,
                'create_datetime' => Yii::$app->time->fullDate,
                'create_unixtime' => Yii::$app->time->unixTime,
            ], false);
            if(!$ticketId){
                throw new \Exception;
            }else{
                $ticket = new DepositAndDrawTicket([
                    'id' => $ticketId,
                ]);
                $transaction->commit();
                return $ticket;
            }
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }
}
