<?php
namespace business\models\parts;

use Yii;
use common\models\parts\Order;
use yii\base\InvalidCallException;

trait RecordTrait{

    private $dateType = [
        'month' => 1,
        'week' => 2,
        'day' => 3,
    ];

    protected $objectFieldName;
    protected $objectFieldValue;
    protected $targetActiveRecord;

    public function generateChart($dateFrom, $dateTo, $dateType){
        switch($dateType){
            case 3:
                $from = $dateFrom;
                $to = $dateTo;
                break;

            case 2:
                $dateTimeFrom = new \DateTime($dateFrom);
                $dateTimeFrom->modify('Monday this week');
                $from = $dateTimeFrom->format('Y-m-d');
                $dateTimeTo = new \DateTime($dateTo);
                $dateTimeTo->modify('Sunday this week');
                $to = $dateTimeTo->format('Y-m-d');
                break;

            case 1:
                $dateTimeFrom = new \DateTime($dateFrom);
                $dateTimeFrom->modify('first day of this month');
                $from = $dateTimeFrom->format('Y-m-d');
                $dateTimeTo = new \DateTime($dateTo);
                $dateTimeTo->modify('last day of this month');
                $to = $dateTimeTo->format('Y-m-d');
                break;

            default:
                return false;
        }
        $AR = (new \ReflectionClass($this->targetActiveRecord))->newInstance();
        switch($dateType){
            case 3:
                $result = $AR::find()->
                    select(['record_date', 'normal_rmb', 'refund_rmb', 'reject_rmb'])->
                    where([$this->objectFieldName => $this->objectFieldValue])->
                    andWhere(['>=', 'record_date', $from])->
                    andWhere(['<=', 'record_date', $to])->
                    asArray()->
                    all();
                break;

            case 2:
                $result = $AR::find()->
                    select(['date_start', 'date_end', 'normal_rmb', 'refund_rmb', 'reject_rmb'])->
                    where([$this->objectFieldName => $this->objectFieldValue])->
                    andWhere(['>=', 'date_start', $from])->
                    andWhere(['<=', 'date_end', $to])->
                    asArray()->
                    all();
                break;

            case 1:
                $result = $AR::find()->
                    select(['record_year', 'record_month', 'normal_rmb', 'refund_rmb', 'reject_rmb'])->
                    where([$this->objectFieldName => $this->objectFieldValue])->
                    andWhere(['>=', 'record_date', $from])->
                    andWhere(['<=', 'record_date', $to])->
                    asArray()->
                    all();
                break;

            default:
                return false;
        }
        switch($dateType){
            case 3:
                return array_map(function($chart){
                    return [
                        'date' => $chart['record_date'],
                        'normal' => $chart['normal_rmb'],
                        'refund' => $chart['refund_rmb'],
                        'reject' => $chart['reject_rmb'],
                    ];
                }, $result);
                break;

            case 2:
                return array_map(function($chart){
                    return [
                        'date' => $chart['date_start'] . ' - ' . $chart['date_end'],
                        'normal' => $chart['normal_rmb'],
                        'refund' => $chart['refund_rmb'],
                        'reject' => $chart['reject_rmb'],
                    ];
                }, $result);
                break;

            case 1:
                return array_map(function($chart){
                    return [
                        'date' => $chart['record_year'] . '-' . $chart['record_month'],
                        'normal' => $chart['normal_rmb'],
                        'refund' => $chart['refund_rmb'],
                        'reject' => $chart['reject_rmb'],
                    ];
                }, $result);
                break;

            default:
                return false;
        }
    }

    public function recordAchievement(array $data, int $dateType, $return = 'throw'){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $AR = (new \ReflectionClass($this->targetActiveRecord))->newInstance();
            $insertData = [
                $this->objectFieldName => $this->objectFieldValue,
            ];
            $date = $data['date'] ?? Yii::$app->time->fullDate;
            $normalRmb = $data['normal_rmb'] ?? 0;
            $refundRmb = $data['refund_rmb'] ?? 0;
            $rejectRmb = $data['reject_rmb'] ?? 0;
            switch($dateType){
                case 1:
                    $month = $this->_generateMonth($date);
                    $insertData['record_year'] = $month['year'];
                    $insertData['record_month'] = $month['month'];
                    $insertData['record_date'] = $month['date'];
                    break;

                case 2:
                    $week = $this->_generateWeek($date);
                    $insertData['date_start'] = $week['start'];
                    $insertData['date_end'] = $week['end'];
                    break;

                case 3:
                    $insertData['record_date'] = $this->_generateDate($date);
                    break;

                default:
                    throw new InvalidCallException;
            }
            $insertData['normal_rmb'] = $normalRmb;
            $insertData['refund_rmb'] = $refundRmb;
            $insertData['reject_rmb'] = $rejectRmb;
            Yii::$app->RQ->AR($AR)->insert($insertData);
            if($dateType == 3){
                call_user_func([$this, 'doAfterRecordAchievement'], $data);
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    private function doAfterRecordAchievement(array $data, $return = 'throw'){
    
    }

    private function _generateDate(string $time){
        $date = new \DateTime($time);
        return $date->format('Y-m-d');
    }

    private function _generateWeek(string $time){
        $date = new \DateTime($time);
        $date->modify('Monday this week');
        $week['start'] = $date->format('Y-m-d');
        $date->modify('Sunday this week');
        $week['end'] = $date->format('Y-m-d');
        return $week;
    }

    private function _generateMonth(string $time){
        $date = new \DateTime($time);
        $month['year'] = (int)$date->format('Y');
        $month['month'] = (int)$date->format('m');
        $month['date'] = $date->format('Y-m-d');
        return $month;
    }
}
