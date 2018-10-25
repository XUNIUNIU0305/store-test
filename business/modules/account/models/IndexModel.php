<?php
namespace business\modules\account\models;

use Yii;
use common\models\Model;
use business\models\parts\Account;
use common\components\handler\Handler;
use common\ActiveRecord\BusinessUserAR;
use common\ActiveRecord\BusinessUserAchievementDayAR;
use business\models\parts\record\BusinessUserRecord;
use yii\web\ForbiddenHttpException;

class IndexModel extends Model{

    const SCE_GET_IDENTITY = 'get_identity';
    const SCE_GET_ACHIEVEMENT = 'get_achievement';
    const SCE_GET_POSITION = 'get_position';
    const SCE_GET_CHART = 'get_chart';

    public $user_id;
    public $date_from;
    public $date_to;
    public $date_type;

    public function scenarios(){
        return [
            self::SCE_GET_IDENTITY => [
                'user_id',
            ],
            self::SCE_GET_ACHIEVEMENT => [
                'user_id',
            ],
            self::SCE_GET_POSITION => [
                'user_id',
            ],
            self::SCE_GET_CHART => [
                'user_id',
                'date_from',
                'date_to',
                'date_type',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['user_id'],
                'default',
                'value' => Yii::$app->user->id,
            ],
            [
                ['user_id', 'date_from', 'date_to', 'date_type'],
                'required',
                'message' => 9001,
            ],
            [
                ['user_id'],
                'business\validators\AccountValidator',
                'status' => [Account::STATUS_NORMAL, Account::STATUS_UNREGISTERED],
                'message' => 9002,
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

    public function getChart(){
        $record = new BusinessUserRecord(['id' => $this->user_id]);
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

    public function getPosition(){
        $user = BusinessUserAR::findOne($this->user_id);
        return [
            'normal' => $user->position_normal_rmb,
            'refund' => $user->position_refund_rmb,
            'reject' => $user->position_reject_rmb,
        ];
    }

    public function getAchievement(){
        $user = BusinessUserAR::findOne($this->user_id);
        $day = new \DateTime(date('Y-m-d'));
        $day->modify('yesterday noon');
        $achievement = BusinessUserAchievementDayAR::findOne([
            'business_user_id' => $this->user_id, 
            'record_date' => $day->format('Y-m-d'),
        ]);
        return [
            'yestarday' => $achievement ? ($achievement->normal_rmb + $achievement->refund_rmb + $achievement->reject_rmb) : 0,
            'life' => $user->all_rmb,
            'position' => $user->position_normal_rmb + $user->position_refund_rmb + $user->position_reject_rmb,
        ];
    }

    public function getIdentity(){
        $account = new Account(['id' => $this->user_id]);
        return Handler::getMultiAttributes($account, [
            'name',
            'mobile',
            'role',
            'area',
            'custom_quantity' => 'area',
            'order_quantity' => 'id',
            '_func' => [
                'role' =>function($role){
                    return $role->name;
                },
                'area' => function($area, $callbackName){
                    if($callbackName == 'area'){
                        return array_map(function($area){
                            return $area->name;
                        }, $area->fullArea);
                    }else{
                        return $area->customQuantity;
                    }
                },
                'id' => function($id){
                    return BusinessUserAR::findOne($id)->order_quantity;
                },
            ],
        ]);
    }
}
