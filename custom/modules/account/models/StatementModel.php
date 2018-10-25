<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/27
 * Time: 上午11:28
 */

namespace custom\modules\account\models;


use common\components\handler\Handler;
use common\models\Model;
use custom\models\parts\trade\Statement;
use Yii;

class StatementModel extends Model
{
    const SCE_GET_LIST = 'get_list';

    public $current_page;
    public $page_size;
    public $search;



    public function scenarios(){
        return [
            self::SCE_GET_LIST => [
                'search',
                'current_page',
                'page_size',
            ]
        ];
    }

    public function rules(){
        return [
            [
                ['current_page', 'page_size'],
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
        ];
    }



    public function getList(){

        $searchData = [];
        if (!empty($this->search) && current($this->search))
        {
            list($startTime, $endTime) = current($this->search);
            $startTime = strtotime($startTime . ' 00:00:00');
            $endTime = strtotime($endTime . ' 23:59:59');
            $searchData = ['between','alteration_unixtime',$startTime, $endTime];
        }
        $data = Yii::$app->CustomUser->statement->provideStatements($this->current_page, $this->page_size,$searchData);

        $statements = array_map(function($statement){
            return new Statement(['id' => $statement['id']]);
        }, $data->models);

        $emptyFunc = function($data){
            return empty($data) ? '' : $data;
        };

        return [
            'statements' => array_map(function($statement)use($emptyFunc){
                return Handler::getMultiAttributes($statement, [
                    'alteration_type'=>'alterationType',
                    'alteration_amount' => 'alterationAmount',
                    'content'=>'content',
                    'alteration_datetime'=>'alterationTime',
                    'rmb_after' => 'RMBAfter',
                    '_func' => [
                        'content' => $emptyFunc,
                    ],
                ]);
            }, $statements),
            'count' => $data->count,
            'total_count' => $data->totalCount,
        ];
    }




}