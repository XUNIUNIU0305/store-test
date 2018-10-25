<?php
namespace business\modules\bank\models;

use Yii;
use common\models\Model;
use common\ActiveRecord\UserDrawAR;
use business\models\parts\trade\nanjing\BusinessAccount;
use common\models\parts\trade\recharge\nanjing\Nanjing;
use common\components\handler\Handler;
use common\models\parts\trade\recharge\nanjing\data\NanjingCallback;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicket;

class CardModel extends Model{

    const SCE_ACTIVATE_ACCOUNT = 'activate_account';
    const SCE_TRANS_AMOUNT = 'trans_amount';
    const SCE_UNBIND_ACCOUNT = 'unbind_account';

    public $ver_seq_no;
    public $check_amount;

    public function scenarios(){
        return [
            self::SCE_ACTIVATE_ACCOUNT => [
                'ver_seq_no',
                'check_amount',
            ],
            self::SCE_TRANS_AMOUNT => [],
            self::SCE_UNBIND_ACCOUNT => [],
        ];
    }

    public function rules(){
        return [
            [
                ['ver_seq_no', 'check_amount'],
                'required',
                'message' => 9001,
            ],
            [
                ['ver_seq_no'],
                'string',
                'length' => [1, 32],
                'tooShort' => 9002,
                'tooLong' => 9002,
                'message' => 9002,
            ],
            [
                ['check_amount'],
                'double',
                'min' => 0.01,
                'max' => 1.00,
                'tooSmall' => 9002,
                'tooBig' => 9002,
                'message' => 9002,
            ],
        ];
    }

    public function unbindAccount(){
        if(Yii::$app->user->isGuest){
            $this->addError('unbindAccount', 9004);
            return false;
        }
        if(!$nanjingAccount = (new BusinessAccount(['id' => Yii::$app->user->id]))->getNanjingAccount(false)){
            $this->addError('unbindAccount', 13401);
            return false;
        }
        if(UserDrawAR::findOne([
            'user_type' => $nanjingAccount->userType,
            'user_id' => $nanjingAccount->userId,
            'status' => [DrawTicket::STATUS_APPLY, DrawTicket::STATUS_PASS],
        ])){
            $this->addError('unbindAccount', 13403);
            return false;
        }
        $nanjing = new Nanjing;
        $result = $nanjing->cancelAccount($nanjingAccount, false);
        if($result === true){
            return [
                'is_success' => true,
                'err_msg' => '',
            ];
        }elseif($result instanceof NanjingCallback){
            return [
                'is_success' => false,
                'err_msg' => $result->RespMsg,
            ];
        }else{
            $this->addError('unbindAccount', 13421);
            return false;
        }
    }

    public function activateAccount(){
        if(!$nanjingAccount = (new BusinessAccount(['id' => Yii::$app->user->id]))->getNanjingAccount(false)){
            $this->addError('activateAccount', 13401);
            return false;
        }
        $nanjing = new Nanjing;
        $result = $nanjing->activateAccount($nanjingAccount, $this->check_amount, $this->ver_seq_no, false);
        if($result === true){
            return [
                'is_success' => true,
                'err_msg' => '',
            ];
        }elseif($result instanceof NanjingCallback){
            return [
                'is_success' => false,
                'err_msg' => $result->RespMsg,
            ];
        }else{
            $this->addError('activateAccount', 13411);
            return false;
        }
    }

    public function transAmount(){
        if(Yii::$app->user->isGuest){
            $this->addError('transAmount', 9004);
            return false;
        }
        if(!$nanjingAccount = (new BusinessAccount(['id' => Yii::$app->user->id]))->getNanjingAccount(false)){
            $this->addError('transAmount', 13401);
            return false;
        }
        $nanjing = new Nanjing;
        $result = $nanjing->activateApply($nanjingAccount, false);
        if(is_string($result)){
            return [
                'is_success' => true,
                'err_msg' => '',
                'ver_seq_no' => $result,
            ];
        }elseif($result instanceof NanjingCallback){
            return [
                'is_success' => false,
                'err_msg' => $result->RespMsg,
                'ver_seq_no' => '',
            ];
        }else{
            $this->addError('transAmount', 13402);
            return false;
        }
    }

    public static function getBindedCard(){
        $unbindedMsg = [
            'is_bind' => false,
            'card' => [],
        ];
        if(Yii::$app->user->isGuest)return $unbindedMsg;
        $businessAccount = new BusinessAccount(['id' => Yii::$app->user->id]);
        if($nanjingAccount = $businessAccount->getNanjingAccount(false)){
            return [
                'is_bind' => true,
                'card' => Handler::getMultiAttributes($nanjingAccount, [
                    'bank_name' => 'bank',
                    'bank_logo' => 'bank',
                    'acct_type' => 'acctType',
                    'acct_name' => 'coveredAcctName',
                    'acct_no' => 'coveredAcctNo',
                    'is_active' => 'isActive',
                    'ver_seq_no' => 'verSeqNo',
                    '_func' => [
                        'bank' => function($bank, $param){
                            if($param == 'bank_name'){
                                return $bank->bankName;
                            }else{
                                return $bank->logoImageUrl;
                            }
                        },
                    ],
                ]),
            ];
        }else{
            return $unbindedMsg;
        }
    }
}
