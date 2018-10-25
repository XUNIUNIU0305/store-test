<?php
namespace business\modules\bank\models;

use Yii;
use common\models\Model;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicketHandler;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicket;
use business\models\parts\trade\nanjing\BusinessAccount;
use common\components\handler\Handler;

class DrawListModel extends Model{

    const SCE_GET_LIST = 'get_list';

    public $current_page;
    public $page_size;
    public $status;

    public function scenarios(){
        return [
            self::SCE_GET_LIST => [
                'current_page',
                'page_size',
                'status',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['current_page', 'page_size'],
                'default',
                'value' => 1,
            ],
            [
                ['current_page', 'page_size', 'status'],
                'required',
                'message' => 9001,
            ],
            [
                ['current_page', 'page_size'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['status'],
                'in',
                'range' => [
                    -1,
                    DrawTicket::STATUS_APPLY,
                    DrawTicket::STATUS_PASS,
                    DrawTicket::STATUS_REJECT,
                    DrawTicket::STATUS_FAILURE,
                    DrawTicket::STATUS_SUCCESS,
                ],
                'message' => 9002,
            ],
        ];
    }

    public function getList(){
        $userAccount = new BusinessAccount(['id' => Yii::$app->user->id]);
        $status = $this->status == -1 ? null : [$this->status];
        $provider = DrawTicketHandler::provide($userAccount, $status, $this->current_page, $this->page_size);
        return Handler::getMultiAttributes($provider, [
            'count',
            'total_count' => 'totalCount',
            'list' => 'models',
            '_func' => [
                'models' => function($models){
                    return array_map(function($model){
                        $drawTicket = new DrawTicket(['id' => $model->id]);
                        return Handler::getMultiAttributes($drawTicket, [
                            'id',
                            'apply_time' => 'applyTime',
                            'draw_number' => 'drawNumber',
                            'bank' => 'nanjingAccount',
                            'rmb',
                            'status',
                            '_func' => [
                                'nanjingAccount' => function($nanjingAccount){
                                    return [
                                        'bank_name' => $nanjingAccount->bank->bankName,
                                        'acct_no' => $nanjingAccount->coveredAcctNo,
                                        'acct_name' => $nanjingAccount->coveredAcctName,
                                    ];
                                },
                            ],
                        ]);
                    }, $models);
                },
            ],
        ]);
    }
}
