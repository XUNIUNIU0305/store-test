<?php
namespace business\modules\site\models;

use Yii;
use common\models\Model;
use yii\db\Query;
use common\components\handler\Handler;
use business\models\parts\Area;

class ContrastModel extends Model{

    const SCE_CONTRAST_AREA = 'contrast_area';

    public $area_ids;
    public $date_from;
    public $date_to;
    public $date_type;

    public function scenarios(){
        return [
            self::SCE_CONTRAST_AREA => [
                'area_ids',
                'date_from',
                'date_to',
                'date_type',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['area_ids', 'date_from', 'date_to', 'date_type'],
                'required',
                'message' => 9001,
            ],
            [
                ['area_ids'],
                'each',
                'rule' => [
                    'business\validators\AreaValidator',
                    'message' => 9002,
                ],
                'allowMessageFromRule' => false,
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
                ['date_type'],
                'in',
                'range' => [1, 2, 3],
                'message' => 13232,
            ],
        ];
    }

    public function contrastArea(){
        $areaIds = array_unique($this->area_ids);
        switch($this->date_type){
            case 1:
                $dateTimeFrom = new \DateTime($this->date_from);
                $dateTimeFrom->modify('first day of this month');
                $from = $dateTimeFrom->format('Y-m-d');
                $dateTimeTo = new \DateTime($this->date_to);
                $dateTimeTo->modify('last day of this month');
                $to = $dateTimeTo->format('Y-m-d');
                $db = '{{%business_area_achievement_month}} AS `achievement`';
                break;

            case 2:
                $dateTimeFrom = new \DateTime($this->date_from);
                $dateTimeFrom->modify('Monday this week');
                $from = $dateTimeFrom->format('Y-m-d');
                $dateTimeTo = new \DateTime($this->date_to);
                $dateTimeTo->modify('Sunday this week');
                $to = $dateTimeTo->format('Y-m-d');
                $db = '{{%business_area_achievement_week}} AS `achievement`';
                break;

            case 3:
                $from = $this->date_from;
                $to = $this->date_to;
                $db = '{{%business_area_achievement_day}} AS `achievement`';
                break;

            default:
                $this->addError('contrastArea', 9002);
                return false;
        }
        $join = '{{%business_area}} AS `area`';
        $on = '`achievement`.`business_area_id` = `area`.`id`';
        switch($this->date_type){
            case 1:
                $query = (new Query)->
                    select([
                        'id' => '`area`.`id`',
                        'name' => '`area`.`name`',
                        'year' => '`achievement`.`record_year`',
                        'month' => '`achievement`.`record_month`',
                        'normal' => '`achievement`.`normal_rmb`',
                        'refund' => '`achievement`.`refund_rmb`',
                        'reject' => '`achievement`.`reject_rmb`',
                    ])->
                    from($db)->
                    innerJoin($join, $on)->
                    where(['`achievement`.`business_area_id`' => $areaIds])->
                    andWhere(['>=', '`achievement`.`record_date`', $from])->
                    andWhere(['<=', '`achievement`.`record_date`', $to])->
                    andWhere(['`area`.`display`' => Area::DISPLAY_ON])->
                    all();
                $result = array_map(function($query){
                    return [
                        'id' => $query['id'],
                        'name' => $query['name'],
                        'date' => $query['year'] . '-' . $query['month'],
                        'achievement' => $query['normal'] + $query['refund'] + $query['reject'],
                    ];
                }, $query);
                break;

            case 2:
                $query = (new Query)->
                    select([
                        'id' => '`area`.`id`',
                        'name' => '`area`.`name`',
                        'date_start' => '`achievement`.`date_start`',
                        'date_end' => '`achievement`.`date_end`',
                        'normal' => '`achievement`.`normal_rmb`',
                        'refund' => '`achievement`.`refund_rmb`',
                        'reject' => '`achievement`.`reject_rmb`',
                    ])->
                    from($db)->
                    innerJoin($join, $on)->
                    where(['`achievement`.`business_area_id`' => $areaIds])->
                    andWhere(['>=', '`achievement`.`date_start`', $from])->
                    andWhere(['<=', '`achievement`.`date_end`', $to])->
                    andWhere(['`area`.`display`' => Area::DISPLAY_ON])->
                    all();
                $result = array_map(function($query){
                    return [
                        'id' => $query['id'],
                        'name' => $query['name'],
                        'date' => $query['date_start'] . ' - ' . $query['date_end'],
                        'achievement' => $query['normal'] + $query['refund'] + $query['reject'],
                    ];
                }, $query);
                break;

            case 3:
                $query = (new Query)->
                    select([
                        'id' => '`area`.`id`',
                        'name' => '`area`.`name`',
                        'date' => '`achievement`.`record_date`',
                        'normal' => '`achievement`.`normal_rmb`',
                        'refund' => '`achievement`.`refund_rmb`',
                        'reject' => '`achievement`.`reject_rmb`',
                    ])->
                    from($db)->
                    innerJoin($join, $on)->
                    where(['`achievement`.`business_area_id`' => $areaIds])->
                    andWhere(['>=', '`achievement`.`record_date`', $from])->
                    andWhere(['<=', '`achievement`.`record_date`', $to])->
                    andWhere(['`area`.`display`' => Area::DISPLAY_ON])->
                    all();
                $result = array_map(function($query){
                    return [
                        'id' => $query['id'],
                        'name' => $query['name'],
                        'date' => $query['date'],
                        'achievement' => $query['normal'] + $query['refund'] + $query['reject'],
                    ];
                }, $query);
                break;

            default:
                $this->addError('contrastArea', 9002);
                return false;
        }
        return $result;
    }
}
