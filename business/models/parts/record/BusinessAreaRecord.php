<?php
namespace business\models\parts\record;

use Yii;
use yii\base\Object;
use business\models\parts\RecordTrait;
use common\ActiveRecord\BusinessAreaAR;
use yii\base\InvalidConfigException;

class BusinessAreaRecord extends Object{

    use RecordTrait;

    public $id;

    protected $AR;

    public function init(){
        if(is_null($this->AR = BusinessAreaAR::findOne($this->id)))throw new InvalidConfigException;
        $this->objectFieldName = 'business_area_id';
        $this->objectFieldValue = $this->AR->id;
    }

    public function generateChartDay($from, $to){
        $this->targetActiveRecord = '\common\ActiveRecord\BusinessAreaAchievementDayAR';
        return $this->generateChart($from, $to, $this->dateType['day']);
    }

    public function generateChartWeek($from, $to){
        $this->targetActiveRecord = '\common\ActiveRecord\BusinessAreaAchievementWeekAR';
        return $this->generateChart($from, $to, $this->dateType['week']);
    }

    public function generateChartMonth($from, $to){
        $this->targetActiveRecord = '\common\ActiveRecord\BusinessAreaAchievementMonthAR';
        return $this->generateChart($from, $to, $this->dateType['month']);
    }

    public function recordDay(array $data, $return = 'throw'){
        $this->targetActiveRecord = '\common\ActiveRecord\BusinessAreaAchievementDayAR';
        return $this->recordAchievement($data, $this->dateType['day'], $return);
    }

    public function recordWeek(array $data, $return = 'throw'){
        $this->targetActiveRecord = '\common\ActiveRecord\BusinessAreaAchievementWeekAR';
        return $this->recordAchievement($data, $this->dateType['week'], $return);
    }

    public function recordMonth(array $data, $return = 'throw'){
        $this->targetActiveRecord = '\common\ActiveRecord\BusinessAreaAchievementMonthAR';
        return $this->recordAchievement($data, $this->dateType['month'], $return);
    }
}
