<?php
namespace business\modules\leader\models;

use Yii;
use common\models\Model;
use business\models\parts\Area;
use common\ActiveRecord\CustomUserAR;
use common\components\handler\Handler;
use common\ActiveRecord\CustomUserAchievementDayAR;
use business\models\parts\record\CustomUserRecord;

class CustomModel extends Model{

    const SCE_VALIDATE_ACCOUNT = 'validate_account';
    const SCE_GET_CUSTOM_INFO = 'get_custom_info';
    const SCE_SET_CUSTOM_AREA = 'set_custom_area';
    const SCE_GET_CUSTOM_ACHIEVEMENT = 'get_custom_achievement';
    const SCE_GET_CUSTOM_CHART = 'get_custom_chart';

    public $account;
    public $area_id;
    public $date_from;
    public $date_to;
    public $date_type;

    public function scenarios(){
        return [
            self::SCE_VALIDATE_ACCOUNT => [
                'account',
            ],
            self::SCE_GET_CUSTOM_INFO => [
                'account',
            ],
            self::SCE_SET_CUSTOM_AREA => [
                'account',
                'area_id',
            ],
            self::SCE_GET_CUSTOM_ACHIEVEMENT => [
                'account',
            ],
            self::SCE_GET_CUSTOM_CHART => [
                'account',
                'date_from',
                'date_to',
                'date_type',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['account', 'area_id', 'date_from', 'date_to', 'date_type'],
                'required',
                'message' => 9001,
            ],
            [
                ['account'],
                'integer',
                'min' => 100000000,
                'max' => 999999999,
                'tooSmall' => 9002,
                'tooBig' => 9002,
                'message' => 9002,
            ],
            [
                ['area_id'],
                'business\validators\AreaValidator',
                'hasChild' => false,
                'message' => 13141,
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

    public function getCustomChart(){
        if(!$CustomUserAR = CustomUserAR::findOne(['account' => $this->account])){
            $this->addError('getCustomChart', 13142);
            return false;
        }
        $record = new CustomUserRecord(['id' => $CustomUserAR->id]);
        switch($this->date_type){
            case 1:
                $chart = $record->generateChartMonth($this->date_from, $this->date_to);
                break;

            case 2:
                $chart = $record->generateChartWeek($this->date_from, $this->date_to);
                break;

            case 3:
                $chart = $record->generateChartDay($this->date_from, $this->date_to);
                break;

            default:
                return [];
                break;
        }
        return $chart;
    }

    public function getCustomAchievement(){
        if(!$CustomUserAR = CustomUserAR::findOne(['account' => $this->account])){
            $this->addError('getCustomAchievement', 13142);
            return false;
        }
        $yesterday = date('Y-m-d', time() - 86400);
        if($yesterdayAR = CustomUserAchievementDayAR::findOne(['custom_user_id' => $CustomUserAR->id, 'record_date' => $yesterday])){
            $yesterdayAchievement = $yesterdayAR->normal_rmb + $yesterdayAR->refund_rmb + $yesterdayAR->reject_rmb;
        }else{
            $yesterdayAchievement = 0;
        }
        return [
            'yesterday' => $yesterdayAchievement,
            'life' => $CustomUserAR->all_rmb,
            'normal' => $CustomUserAR->all_normal_rmb,
            'refund' => $CustomUserAR->all_refund_rmb,
            'reject' => $CustomUserAR->all_reject_rmb,
        ];
    }

    public function setCustomArea(){
        if(!$this->validateAccount()){
            $this->addError('setCustomArea', 13142);
            return false;
        }
        $targetArea = new Area(['id' => $this->area_id]);
        if(($userArea = Yii::$app->BusinessUser->account->area)->level->level != Area::LEVEL_UNDEFINED){
            $differLevel = $targetArea->level->level - $userArea->level->level;
            $parentArea = $targetArea;
            for($i = 0; $i < $differLevel; $i++){
                $parentArea = $parentArea->parent;
            }
            if($parentArea->id != $userArea->id){
                $this->addError('setCustomArea', 13141);
                return false;
            }
        }
        if($targetArea->bindCustom($this->account, false)){
            return true;
        }else{
            $this->addError('setCustomArea', 13143);
            return false;
        }
    }

    public function validateAccount(){
        if(!$customUser = CustomUserAR::findOne(['account' => $this->account]))return false;
        if(($userTopArea = Yii::$app->BusinessUser->account->TopArea)->level->level == Area::LEVEL_UNDEFINED)return true;
        $accountTopArea = (new Area(['id' => $customUser->business_area_id]))->topArea;
        return $userTopArea->id == $accountTopArea->id;
    }

    public function getCustomInfo(){
        if(!$this->validateAccount()){
            $this->addError('getCustomInfo', 13131);
            return false;
        }
        $customUser = CustomUserAR::findOne(['account' => $this->account]);
        return Handler::getMultiAttributes($customUser, [
            'account',
            'nick_name',
            'mobile',
            'email',
            'area' => 'business_area_id',
            '_func' => [
                'mobile' => function($mobile){
                    return $mobile ? : '';
                },
                'business_area_id' => function($areaId){
                    $area = new Area(['id' => $areaId]);
                    return array_map(function($area){
                        return Handler::getMultiAttributes($area, [
                            'id',
                            'name',
                        ]);
                    }, $area->fullArea);
                },
            ],
        ]);
    }
}
