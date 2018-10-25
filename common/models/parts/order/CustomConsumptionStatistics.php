<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-4-27
 * Time: 下午2:21
 */
namespace common\models\parts\order;

use common\ActiveRecord\CustomConsumptionStatisticsAR;
use common\models\Object;
use yii\base\InvalidCallException;
use yii\db\Exception;
use Yii;

class CustomConsumptionStatistics extends Object
{
    public $id;

    protected $AR;

    public function init()
    {
        if ($this->id) {
            if (!$this->id || !$this->AR = CustomConsumptionStatisticsAR::findOne($this->id)) {
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

        if (!isset($data['custom_user_id']) || !isset($data['daily_order_count']) || !isset($data['daily_consumption_amount'])) {
            return false;
        }

        if ($customConsumptionStatistics = CustomConsumptionStatisticsAR::findOne(['custom_user_id' => $data['custom_user_id']])) {
            try {
                return Yii::$app->RQ->AR($customConsumptionStatistics)->update([
                    'statistics_date' => date('Y-m-d'),
                    'business_area_id' => $data['business_area_id'],
                    'business_quaternary_area_id' => $data['business_quaternary_area_id'],
                    'business_tertiary_area_id' => $data['business_tertiary_area_id'],
                    'business_secondary_area_id' => $data['business_secondary_area_id'],
                    'business_top_area_id' => $data['business_top_area_id'],
                    'daily_order_count' => $data['daily_order_count'],
                    'daily_consumption_amount' => $data['daily_consumption_amount'],
                    'create_unixtime' => time(),
                ]);
            } catch (\Exception $e) {
                return false;
            }
        } else {
            $data['create_unixtime'] = time();
            if (Yii::$app->RQ->AR(new CustomConsumptionStatisticsAR)->insert($data, false)) {
                return true;
            } else {
                return false;
            }
        }


    }
}
