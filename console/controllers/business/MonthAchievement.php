<?php
namespace console\controllers\business;

use Yii;

trait MonthAchievement{

    public function actionRecordMonth(){
        $recordTarget = [
            '\common\ActiveRecord\BusinessAreaAR',
            '\common\ActiveRecord\BusinessUserAR',
            '\common\ActiveRecord\CustomUserAR',
        ];
        $recordDay = [
            '\common\ActiveRecord\BusinessAreaAchievementDayAR',
            '\common\ActiveRecord\BusinessUserAchievementDayAR',
            '\common\ActiveRecord\CustomUserAchievementDayAR',
        ];
        $recordObj = [
            '\business\models\parts\record\BusinessAreaRecord',
            '\business\models\parts\record\BusinessUserRecord',
            '\business\models\parts\record\CustomUserRecord',
        ];
        $targetUser = [
            'business_area_id',
            'business_user_id',
            'custom_user_id',
        ];
        $dateTime = new \DateTime(Yii::$app->time->fullDate);
        $dateTime->modify('first day of last month');
        $firstDay = $dateTime->format('Y-m-d');
        $dateTime->modify('last day of this month');
        $lastDay = $dateTime->format('Y-m-d');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($recordTarget as $key => $target){
                $targetAR = new $target;
                $dayAR = new $recordDay[$key];
                $targetIds = Yii::$app->RQ->AR($targetAR)->column([
                    'select' => ['id'],
                ]);
                foreach($targetIds as $targetId){
                    $lastMonthData = $dayAR::find()->
                        select([
                            'normal' => 'SUM(`normal_rmb`)',
                            'refund' => 'SUM(`refund_rmb`)',
                            'reject' => 'SUM(`reject_rmb`)',
                        ])->
                        where([
                            $targetUser[$key] => $targetId,
                        ])->
                        andWhere([
                            '>=', 'record_date', $firstDay,
                        ])->
                        andWhere([
                            '<=', 'record_date', $lastDay,
                        ])->
                        asArray()->
                        one();
                    $record = new $recordObj[$key](['id' => $targetId]);
                    $record->recordMonth([
                        'date' => $firstDay,
                        'normal_rmb' => $lastMonthData['normal'] ?? 0,
                        'refund_rmb' => $lastMonthData['refund'] ?? 0,
                        'reject_rmb' => $lastMonthData['reject'] ?? 0,
                    ]);
                }
            }
            $transaction->commit();
            return 0;
        }catch(\Exception $e){
            $transaction->rollBack();
            return 1;
        }
    }
}
