<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-4-27
 * Time: 上午10:45
 */

namespace console\controllers;

use common\ActiveRecord\BusinessAreaAR;
use common\ActiveRecord\CustomConsumptionStatisticsAR;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\OrderCustomRecordAR;
use common\models\parts\order\BusinessAreaConsumptionStatistics;
use common\models\parts\order\CustomConsumptionStatistics;
use console\controllers\basic\Controller;

class ConsumptionStatisticsController extends Controller
{
    public function actionIndex()
    {
        $currentDate = date('Y-m-d') . ' 00:00:00';
        $this->handleCustom($currentDate);
        $this->handleArea($currentDate);
        return 0;
    }

    /*
     * 按门店进行消费统计
     */
    public function handleCustom($currentDate)
    {
        $todayLeftTime = strtotime($currentDate);
        $todayLightTime = $todayLeftTime + 3600 * 24 - 1;

        $customUserIds = CustomUserAR::find()
                                    ->select(['id'])
                                    ->where(['status' => 0])
                                    ->asArray()
                                    ->column();
        foreach ($customUserIds as $customUserId) {
            $records = OrderCustomRecordAR::find()
                                            ->where(['custom_user_id' => $customUserId])
                                            ->andWhere(['between', 'create_unixtime', $todayLeftTime, $todayLightTime])
                                            ->all();
            $data = [];
            $data['custom_user_id'] = $customUserId;
            $data['statistics_date'] = $currentDate;
            if (!empty($records)) {
                $data['business_area_id'] = $records[0]->business_area_id;
                $data['business_top_area_id'] = $records[0]->business_top_area_id;
                $data['business_secondary_area_id'] = $records[0]->business_secondary_area_id;
                $data['business_tertiary_area_id'] = $records[0]->business_tertiary_area_id;
                $data['business_quaternary_area_id'] = $records[0]->business_quaternary_area_id;
            } else {
                $user = CustomUserAR::find()
                            ->select(['business_area_id', 'business_top_area_id', 'business_secondary_area_id',
                                'business_tertiary_area_id', 'business_quaternary_area_id'])
                            ->where(['id' => $customUserId])
                            ->asArray()
                            ->one();
                $data['business_area_id'] = $user['business_area_id'];
                $data['business_top_area_id'] = $user['business_top_area_id'];
                $data['business_secondary_area_id'] = $user['business_secondary_area_id'];
                $data['business_tertiary_area_id'] = $user['business_tertiary_area_id'];
                $data['business_quaternary_area_id'] = $user['business_quaternary_area_id'];
                unset($user);
            }
            $data['daily_order_count'] = 0;
            $data['daily_consumption_amount'] = 0;
            foreach ($records as $record) {
                ++$data['daily_order_count'];
                $data['daily_consumption_amount'] += $record->rmb;
            }
            CustomConsumptionStatistics::add($data);
            unset($data);
            unset($records);
        }
        \common\ActiveRecord\CustomConsumptionStatisticsAR::deleteAll([
            'not in', 'custom_user_id', $customUserIds,
        ]);
    }

    /*
     * 按地区进行消费统计
     */
    public function handleArea($currentDate)
    {
        $areaIds = BusinessAreaAR::find()
                                ->select(['id', 'level', 'custom_quantity'])
                                ->where(['display' => 1])
                                ->asArray()
                                ->all();
        foreach ($areaIds as $item) {
            switch ($item['level']) {
                case 1:
                    $areaLevelName = 'business_top_area_id';
                    break;
                case 2:
                    $areaLevelName = 'business_secondary_area_id';
                    break;
                case 3:
                    $areaLevelName = 'business_tertiary_area_id';
                    break;
                case 4:
                    $areaLevelName = 'business_quaternary_area_id';
                    break;
                case 5:
                    $areaLevelName = 'business_area_id';
                    break;
                default:
                    $areaLevelName = 0;
                    break;
            }

            $customStatistics = CustomConsumptionStatisticsAR::find()
                                        ->select(['daily_consumption_amount'])
                                        ->where([$areaLevelName => $item['id']])
                                        ->andWhere(['>', 'daily_consumption_amount', 0])
                                        ->asArray()
                                        ->all();
            $data = [];
            $data['daily_consumption_amount'] = 0;
            $data['daily_custom_consumption_count'] = 0;
            foreach ($customStatistics as $statistic) {
                $data['daily_consumption_amount'] += $statistic['daily_consumption_amount'];
                ++$data['daily_custom_consumption_count'];
            }
            $data['daily_custom_unconsumption_count'] = $item['custom_quantity'] - $data['daily_custom_consumption_count'];
            $data['area_id'] = $item['id'];
            $data['area_level'] = $item['level'];
            $data['statistics_date'] = $currentDate;

            BusinessAreaConsumptionStatistics::add($data);
            unset($areaLevelName);
            unset($customStatistics);
            unset($data);
        }
    }
}
