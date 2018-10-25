<?php
namespace admin\modules\nanjing\models;

use Yii;
use common\models\Model;
use common\components\handler\Handler;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicket;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicketHandler;

class DrawReviewListModel extends Model{

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
                ['status', 'current_page', 'page_size'],
                'required',
                'message' => 9001,
            ],
            [
                ['status'],
                'in',
                'range' => array_merge(
                    [-1],
                    DrawTicket::getStatuses()
                ),
                'message' => 9002,
            ],
            [
                ['current_page', 'page_size'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
        ];
    }

    public function getList(){
        $status = $this->status == -1 ? null : [$this->status];
        $provider = DrawTicketHandler::provide(null, $status, $this->current_page, $this->page_size);
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
                            'draw_number' => 'drawNumber',
                            'rmb',
                            'account' => 'userAccount',
                            'bank' => 'nanjingAccount',
                            'apply_time' => 'applyTime',
                            'status',
                            '_func' => [
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
