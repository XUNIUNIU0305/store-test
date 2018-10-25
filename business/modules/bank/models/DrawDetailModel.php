<?php
namespace business\modules\bank\models;

use Yii;
use common\models\Model;
use common\components\handler\Handler;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicket;

class DrawDetailModel extends Model{

    const SCE_GET_DETAIL = 'get_detail';

    public $draw_id;

    public function scenarios(){
        return [
            self::SCE_GET_DETAIL => [
                'draw_id',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['draw_id'],
                'required',
                'message' => 9001,
            ],
            [
                ['draw_id'],
                'business\modules\bank\validators\DrawIdValidator',
                'userId' => Yii::$app->user->id,
                'message' => 9002,
            ],
        ];
    }

    public function getDetail(){
        $drawTicket = new DrawTicket(['id' => $this->draw_id]);
        return Handler::getMultiAttributes($drawTicket, [
            'id',
            'draw_number' => 'drawNumber',
            'rmb',
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
                'nanjingAccount' => function($nanjingAccount){
                    return [
                        'bank_name' => $nanjingAccount->bank->bankName,
                        'acct_no' => $nanjingAccount->coveredAcctNo,
                        'acct_name' => $nanjingAccount->coveredAcctName,
                    ];
                },
                'passTime' => function($time){
                    return $time ? : '';
                },
                'rejectTime' => function($time){
                    return $time ? : '';
                },
                'failureTime' => function($time){
                    return $time ? : '';
                },
                'successTime' => function($time){
                    return $time ? : '';
                },
            ],
        ]);
    }
}
