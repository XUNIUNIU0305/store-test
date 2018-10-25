<?php
namespace business\modules\site\models;

use Yii;
use common\models\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use common\components\handler\Handler;

class AreaModel extends Model{

    const SCE_GET_AREA_CHART = 'get_area_chart';

    public $level;
    public $date_from;
    public $date_to;
    public $current_page;
    public $page_size;
    public $sort_field;
    public $sort_type;

    public function scenarios(){
        return [
            self::SCE_GET_AREA_CHART => [
                'level',
                'date_from',
                'date_to',
                'current_page',
                'page_size',
                'sort_field',
                'sort_type',
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
                ['sort_field'],
                'default',
                'value' => 'id',
            ],
            [
                ['sort_type'],
                'default',
                'value' => 0,
            ],
            [
                ['level', 'date_from', 'date_to', 'current_page', 'page_size'],
                'required',
                'message' => 9001,
            ],
            [
                ['level'],
                'in',
                'range' => [1, 2, 3, 4, 5],
                'message' => 9002,
            ],
            [
                ['date_from'],
                'business\validators\DateValidator',
                'beforeDate' => $this->date_to,
                'message' => 13231,
            ],
            [
                ['date_to'],
                'business\validators\DateValidator',
                'message' => 13231,
            ],
            [
                ['current_page', 'page_size'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['sort_field'],
                'in',
                'range' => ['normal', 'refund', 'reject', 'id'],
                'message' => 9002,
            ],
            [
                ['sort_type'],
                'in',
                'range' => [0, 1],
                'message' => 9002,
            ],
        ];
    }

    public function getAreaChart(){
        $orderBy = $this->sort_field == 'id' ? '`id`' : $this->sort_field;
        $sortType = $this->sort_type == 0 ? SORT_ASC : SORT_DESC;
        $provider = new ActiveDataProvider([
            'query' => (new Query)->
                select([
                    'id' => '`user`.`id`',
                    'name' => '`user`.`name`',
                    'normal' => 'SUM(`achievement`.`normal_rmb`)',
                    'refund' => 'SUM(`achievement`.`refund_rmb`)',
                    'reject' => 'SUM(`achievement`.`reject_rmb`)',
                ])->
                from('{{%business_area_achievement_day}} AS `achievement`')->
                innerJoin('{{%business_area}} AS `user`', '`achievement`.`business_area_id` = `user`.`id`')->
                where(['`user`.`level`' => $this->level])->
                andWhere(['>=', '`achievement`.`record_date`', $this->date_from])->
                andWhere(['<=', '`achievement`.`record_date`', $this->date_to])->
                orderBy([$orderBy => $sortType])->
                groupBy('`achievement`.`business_area_id`'),
            'pagination' => [
                'page' => $this->current_page - 1,
                'pageSize' => $this->page_size,
            ],
        ]);
        return Handler::getMultiAttributes($provider, [
            'count',
            'total_count' => 'totalCount',
            'list' => 'models',
        ]);
    }
}
