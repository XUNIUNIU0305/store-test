<?php
namespace business\models\parts\record;

use Yii;
use yii\base\Object;
use business\models\parts\RecordTrait;
use yii\base\InvalidConfigException;
use common\ActiveRecord\CustomUserAR;

class CustomUserRecord extends Object{

    use RecordTrait;

    public $id;

    protected $AR;

    public function init(){
        if(is_null($this->AR = CustomUserAR::findOne($this->id)))throw new InvalidConfigException;
        $this->objectFieldName = 'custom_user_id';
        $this->objectFieldValue = $this->AR->id;
    }

    public function generateChartDay($from, $to){
        $this->targetActiveRecord = '\common\ActiveRecord\CustomUserAchievementDayAR';
        return $this->generateChart($from, $to, $this->dateType['day']);
    }

    public function generateChartWeek($from, $to){
        $this->targetActiveRecord = '\common\ActiveRecord\CustomUserAchievementWeekAR';
        return $this->generateChart($from, $to, $this->dateType['week']);
    }

    public function generateChartMonth($from, $to){
        $this->targetActiveRecord = '\common\ActiveRecord\CustomUserAchievementMonthAR';
        return $this->generateChart($from, $to, $this->dateType['month']);
    }

    public function recordDay(array $data, $return = 'throw'){
        $this->targetActiveRecord = '\common\ActiveRecord\CustomUserAchievementDayAR';
        return $this->recordAchievement($data, $this->dateType['day'], $return);
    }

    public function recordWeek(array $data, $return = 'throw'){
        $this->targetActiveRecord = '\common\ActiveRecord\CustomUserAchievementWeekAR';
        return $this->recordAchievement($data, $this->dateType['week'], $return);
    }

    public function recordMonth(array $data, $return = 'throw'){
        $this->targetActiveRecord = '\common\ActiveRecord\CustomUserAchievementMonthAR';
        return $this->recordAchievement($data, $this->dateType['month'], $return);
    }

    private function doAfterRecordAchievement(array $data, $return = 'throw'){
        $normalRmb = $data['normal_rmb'] ?? 0;
        $refundRmb = $data['refund_rmb'] ?? 0;
        $rejectRmb = $data['reject_rmb'] ?? 0;
        $allRmb = $normalRmb + $refundRmb + $rejectRmb;
        return Yii::$app->RQ->AR($this->AR)->update([
            'all_rmb' => $this->AR->all_rmb + $allRmb,
            'all_normal_rmb' => $this->AR->all_normal_rmb + $normalRmb,
            'all_refund_rmb' => $this->AR->all_refund_rmb + $refundRmb,
            'all_reject_rmb' => $this->AR->all_reject_rmb + $rejectRmb,
        ], $return);
    }
}
