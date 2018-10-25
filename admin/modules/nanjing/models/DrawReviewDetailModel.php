<?php
namespace admin\modules\nanjing\models;

use Yii;
use common\models\Model;
use common\ActiveRecord\UserDrawAR;
use common\components\handler\Handler;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicket;
use common\models\parts\trade\recharge\nanjing\Nanjing;
use common\models\parts\trade\recharge\nanjing\data\NanjingCallback;

class DrawReviewDetailModel extends Model{

    const SCE_GET_DETAIL = 'get_detail';
    const SCE_PASS = 'pass';
    const SCE_REJECT = 'reject';

    public $draw_id;
    public $msg;

    public function scenarios(){
        return [
            self::SCE_GET_DETAIL => [
                'draw_id',
            ],
            self::SCE_PASS => [
                'draw_id',
            ],
            self::SCE_REJECT => [
                'draw_id',
                'msg',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['draw_id', 'msg'],
                'required',
                'message' => 9001,
            ],
            [
                ['draw_id'],
                'exist',
                'targetClass' => UserDrawAR::className(),
                'targetAttribute' => 'id',
                'message' => 9002,
            ],
            [
                ['msg'],
                'string',
                'length' => [1, 255],
                'tooShort' => 9002,
                'tooLong' => 9002,
                'message' => 9002,
            ],
        ];
    }

    public function reject(){
        Yii::$app->db->queryMaster = true;
        $drawTicket = new DrawTicket(['id' => $this->draw_id]);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $drawTicket->verifyMsg = $this->msg;
            $drawTicket->userAccount->wallet->thaw($drawTicket);
            $drawTicket->status = DrawTicket::STATUS_REJECT;
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('reject', 5371);
            return false;
        }
    }

    public function pass(){
        Yii::$app->db->queryMaster = true;
        $drawTicket = new DrawTicket(['id' => $this->draw_id]);
        $nanjing = new Nanjing;
        $result = $nanjing->transOfDraw($drawTicket, false);
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
            $this->addError('pass', 5372);
            return false;
        }
    }

    public function getDetail(){
        $drawTicket = new DrawTicket(['id' => $this->draw_id]);
        $validateTime = function($time){
            return $time ? : '';
        };
        return Handler::getMultiAttributes($drawTicket, [
            'id',
            'draw_number' => 'drawNumber',
            'rmb',
            'account' => 'userAccount',
            'bank' => 'nanjingAccount',
            'apply_time' => 'applyTime',
            'pass_time' => 'passTime',
            'reject_time' => 'rejectTime',
            'failure_time' => 'failureTime',
            'success_time' => 'successTime',
            'verify_msg' => 'verifyMsg',
            'handle_err_msg' => 'handleErrMsg',
            'status',
            '_func' => [
                'passTime' => $validateTime,
                'rejectTime' => $validateTime,
                'failureTime' => $validateTime,
                'successTime' => $validateTime,
                'userAccount' => function($userAccount){
                    return [
                        'user_type' => $userAccount->userType,
                        'user_account' => $userAccount->userAccount,
                        'user_phone' => $userAccount->mobilePhone,
                    ];
                },
                'nanjingAccount' => function($nanjingAccount){
                    return [
                        'bank_name' => $nanjingAccount->bank->bankName,
                        'acct_no' => $nanjingAccount->coveredAcctNo,
                        'acct_name' => $nanjingAccount->coveredAcctName,
                        'acct_type' => $nanjingAccount->acctType,
                        'acct_phone' => $nanjingAccount->mobilePhone,
                        'create_time' => $nanjingAccount->createDatetime,
                    ];
                },
            ],
        ]);
    }
}
