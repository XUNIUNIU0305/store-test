<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/4/18
 * Time: 上午11:06
 */

namespace admin\modules\info\models;


use admin\components\handler\StatementHandler;
use common\models\Model;
use common\models\parts\custom\CustomUser;
use custom\models\parts\trade\Statement;
use common\components\handler\Handler;


class StatementModel extends Model
{
    const SCE_STATEMENT_LIST = 'get_list';

    public $current_page;
    public $page_size;
    public $search;


    public function rules()
    {
        return [
            [
                [
                    'current_page',
                    'page_size'
                ],
                'required',
                'message' => 9001,
            ],
            [
                [
                    'current_page',
                    'page_size'
                ],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],

        ];

    }

    public function scenarios()
    {
        return [
            self::SCE_STATEMENT_LIST => [
                'search',
                'current_page',
                'page_size',
            ],
        ];
    }


    public function getList()
    {
        $searchData = [];
        if (!empty($this->search) && current($this->search))
        {
            try{
                //获取一个customer对象
                $customUser = new CustomUser([
                    'account' => current($this->search),
                ]);
                $searchData['custom_user_id'] = $customUser->id;
            }catch (\Exception $e){
                $searchData['custom_user_id'] = 0;
            }
        }
        $data = StatementHandler::provideStatements($this->current_page, $this->page_size, $searchData);

        $statements = array_map(function ($statement)
        {
            return new Statement(['id' => $statement['id']]);

        }, $data->models);

        $emptyFunc = function ($data)
        {
            return empty($data) ? '' : $data;
        };

        return [
            'statements' => array_map(function ($statement) use ($emptyFunc)
            {
                return Handler::getMultiAttributes($statement, [
                    'alteration_type' => 'alterationType',
                    'alteration_amount' => 'alterationAmount',
                    'account' => 'account',
                    'content' => 'content',
                    'alteration_datetime' => 'alterationTime',
                    'rmb_after' => 'RMBAfter',
                    '_func' => [
                        'content' => $emptyFunc,
                        'account' => $emptyFunc,
                    ],
                ]);
            }, $statements),
            'count' => $data->count,
            'total_count' => $data->totalCount,
        ];
    }


}