<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-4-28
 * Time: 上午9:48
 */

namespace common\models\parts\order;

use common\ActiveRecord\BusinessAreaConsumptionStatisticsAR;
use common\models\Object;
use yii\base\InvalidCallException;
use yii\db\Exception;
use Yii;


class BusinessAreaConsumptionStatistics extends Object
{
    public $id;

    protected $AR;

    public function init()
    {
        if ($this->id) {
            if (!$this->id || !$this->AR = BusinessAreaConsumptionStatisticsAR::findOne($this->id)) {
                throw new InvalidCallException();
            }
        } else {
            throw new InvalidCallException('invalid id');
        }
    }

    public static function add(Array $data, $return = 'throw')
    {
        if (empty($data)) {
            return false;
        }

        if (!isset($data['statistics_date']) || !isset($data['daily_custom_consumption_count'])
            || !isset($data['daily_custom_unconsumption_count']) || !isset($data['daily_consumption_amount'])) {
            return false;
        }

        if ($areaConsumptionStatistics = BusinessAreaConsumptionStatisticsAR::findOne(['area_id' => $data['area_id']])) {
            try {
                $areaConsumptionStatistics->daily_custom_consumption_count = $data['daily_custom_consumption_count'];
                $areaConsumptionStatistics->daily_custom_unconsumption_count = $data['daily_custom_unconsumption_count'];
                $areaConsumptionStatistics->daily_consumption_amount = $data['daily_consumption_amount'];
                $areaConsumptionStatistics->statistics_date = date('Y-m-d');
                $areaConsumptionStatistics->create_unixtime = time();
                if ($areaConsumptionStatistics->update()) {
                    return true;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                return false;
            }
        } else {
            $data['create_unixtime'] = time();
            if (Yii::$app->RQ->AR(new BusinessAreaConsumptionStatisticsAR)->insert($data, false)) {
                return true;
            } else {
                return false;
            }
        }


    }


}
