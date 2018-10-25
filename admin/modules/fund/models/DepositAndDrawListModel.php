<?php
namespace admin\modules\fund\models;

use Yii;
use common\models\Model;
use common\components\handler\Handler;
use admin\modules\fund\models\parts\DepositAndDrawList;
use admin\modules\fund\models\parts\DepositAndDrawTicket;

class DepositAndDrawListModel extends Model{

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
                ['current_page'],
                'default',
                'value' => 1,
            ],
            [
                ['page_size'],
                'default',
                'value' => 10,
            ],
            [
                ['current_page', 'page_size', 'status'],
                'required',
                'message' => 9001,
            ],
            [
                ['current_page'],
                'integer',
                'min' => 1,
                'tooSmall' => 5451,
                'message' => 5451,
            ],
            [
                ['status'],
                'in',
                'range' => DepositAndDrawTicket::getStatuses(),
                'message' => 5452,
            ],
        ];
    }

    public function getList(){
        $provider = DepositAndDrawList::provideList($this->current_page, $this->page_size, $this->status);
        return Handler::getMultiAttributes($provider, [
            'count',
            'total_count' => 'totalCount',
            'list' => 'models',
            '_func' => [
                'models' => function($models){
                    return array_map(function($model){
                        $ticket = new DepositAndDrawTicket([
                            'id' => $model['id'],
                        ]);
                        return Handler::getMultiAttributes($ticket, [
                            'id',
                            'operate_type' => 'operateType',
                            'user_type' => 'targetUserType',
                            'user_account' => 'targetUserAccount',
                            'amount',
                            'operate_brief' => 'operateBrief',
                            'status',
                            'create_time' => 'createTime',
                            'pass_time' => 'passTime',
                            'operate_time' => 'operateTime',
                            'cancel_time' => 'cancelTime',
                        ]);
                    }, $models);
                },
            ],
        ]);
    }
}
