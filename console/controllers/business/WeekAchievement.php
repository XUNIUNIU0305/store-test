<?php
namespace console\controllers\business;

use Yii;

trait WeekAchievement{

    public function actionRecordWeek(){
        $recordTarget = [
            '\common\ActiveRecord\BusinessAreaAR',
            '\common\ActiveRecord\BusinessUserAR',
            '\common\ActiveRecord\CustomUserAR'
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
        $dateTime->modify('Monday last week');
        $monday = $dateTime->format('Y-m-d');
        $dateTime->modify('Sunday this week');
        $sunday = $dateTime->format('Y-m-d');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($recordTarget as $key => $target){
                $targetAR = new $target;
                $dayAR = new $recordDay[$key];
                $targetIds = Yii::$app->RQ->AR($targetAR)->column([
                    'select' => ['id'],
                ]);
                foreach($targetIds as $targetId){
                    $lastWeekData = $dayAR::find()->
                        select([
                            'normal' => 'SUM(`normal_rmb`)',
                            'refund' => 'SUM(`refund_rmb`)',
                            'reject' => 'SUM(`reject_rmb`)',
                        ])->
                        where([
                            $targetUser[$key] => $targetId,
                        ])->
                        andWhere([
                            '>=', 'record_date', $monday,
                        ])->
                        andWhere([
                            '<=', 'record_date', $sunday,
                        ])->
                        asArray()->
                        one();
                    $record = new $recordObj[$key](['id' => $targetId]);
                    $record->recordWeek([
                        'date' => $monday,
                        'normal_rmb' => $lastWeekData['normal'] ?? 0,
                        'refund_rmb' => $lastWeekData['refund'] ?? 0,
                        'reject_rmb' => $lastWeekData['reject'] ?? 0,
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
