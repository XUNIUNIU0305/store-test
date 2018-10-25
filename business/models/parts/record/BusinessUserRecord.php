<?php
namespace business\models\parts\record;

use Yii;
use yii\base\Object;
use business\models\parts\RecordTrait;
use yii\base\InvalidConfigException;
use common\ActiveRecord\BusinessUserAR;

class BusinessUserRecord extends Object{

    use RecordTrait;

    public $id;

    protected $AR;

    public function init(){
        if(is_null($this->AR = BusinessUserAR::findOne($this->id)))throw new InvalidConfigException;
        $this->objectFieldName = 'business_user_id';
        $this->objectFieldValue = $this->AR->id;
    }

    public function generateChartDay($from, $to){
        $this->targetActiveRecord = '\common\ActiveRecord\BusinessUserAchievementDayAR';
        return $this->generateChart($from, $to, $this->dateType['day']);
    }

    public function generateChartWeek($from, $to){
        $this->targetActiveRecord = '\common\ActiveRecord\BusinessUserAchievementWeekAR';
        return $this->generateChart($from, $to, $this->dateType['week']);
    }

    public function generateChartMonth($from, $to){
        $this->targetActiveRecord = '\common\ActiveRecord\BusinessUserAchievementMonthAR';
        return $this->generateChart($from, $to, $this->dateType['month']);
    }

    public function recordDay(array $data, $return = 'throw'){
        $this->targetActiveRecord = '\common\ActiveRecord\BusinessUserAchievementDayAR';
        return $this->recordAchievement($data, $this->dateType['day'], $return);
    }

    public function recordWeek(array $data, $return = 'throw'){
        $this->targetActiveRecord = '\common\ActiveRecord\BusinessUserAchievementWeekAR';
        return $this->recordAchievement($data, $this->dateType['week'], $return);
    }

    public function recordMonth(array $data, $return = 'throw'){
        $this->targetActiveRecord = '\common\ActiveRecord\BusinessUserAchievementMonthAR';
        return $this->recordAchievement($data, $this->dateType['month'], $return);
    }

    public function recordOrderQuantity(int $quantity, $return = 'throw'){
        if($quantity < 0)return Yii::$app->EC->callback($return, 'P_int');
        return Yii::$app->RQ->AR($this->AR)->update([
            'order_quantity' => $this->AR->order_quantity + $quantity,
        ], $return);
    }

    private function doAfterRecordAchievement(array $data, $return = 'throw'){
        $normalRmb = $data['normal_rmb'] ?? 0;
        $refundRmb = $data['refund_rmb'] ?? 0;
        $rejectRmb = $data['reject_rmb'] ?? 0;
        $allRmb = $normalRmb + $refundRmb + $rejectRmb;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            Yii::$app->RQ->AR($this->AR)->update([
                'all_rmb' => $this->AR->all_rmb + $allRmb,
                'position_normal_rmb' => $this->AR->position_normal_rmb + $normalRmb,
                'position_refund_rmb' => $this->AR->position_refund_rmb + $refundRmb,
                'position_reject_rmb' => $this->AR->position_reject_rmb + $rejectRmb,
            ]);
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }
}
