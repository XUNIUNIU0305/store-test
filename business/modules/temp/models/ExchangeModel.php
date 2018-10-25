<?php
namespace business\modules\temp\models;

use Yii;
use common\models\Model;
use business\models\parts\Area;
use common\ActiveRecord\CustomUserActivityLimitAR;
use custom\models\parts\temp\OrderLimit\ActivityLimit;
use common\models\parts\custom\CustomUser;
use custom\models\parts\temp\OrderLimit\activity\ApiForBusiness;

class ExchangeModel extends Model{

    const SCE_EXCHANGE_QUERY = 'exchange_query';
    const SCE_EXCHANGE = 'exchange';

    public $pick_id;

    public function scenarios(){
        return [
            self::SCE_EXCHANGE_QUERY => [
                'pick_id',
            ],
            self::SCE_EXCHANGE => [
                'pick_id',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['pick_id'],
                'required',
                'message' => 9001,
            ],
        ];
    }

    public function exchangeQuery(){
        if(Yii::$app->BusinessUser->account->area->level->level != Area::LEVEL_QUATERNARY){
            $this->addError('exchangeQuery', 13251);
            return false;
        }
        $activityAR = CustomUserActivityLimitAR::findOne(['pick_id' => $this->pick_id]);
        if(!$activityAR){
            return [
                'status' => false,
                'error' => 1,
            ];
        }
        if($activityAR->paid == ActivityLimit::STATUS_UNPAID){
            return [
                'status' => false,
                'error' => 2,
            ];
        }
        if($activityAR->business_area_id != ActivityLimit::UNEXCHANGE){
            return [
                'status' => false,
                'error' => 3,
            ];
        }
        $customUser = new CustomUser(['id' => $activityAR->custom_user_id]);
        if(($quaternaryArea = $customUser->area->parent)->id != Yii::$app->BusinessUser->account->area->id){
            return [
                'status' => false,
                'error' => 4,
                'message' => $quaternaryArea->name,
            ];
        }
        return [
            'status' => true,
            'pick_id' => $activityAR->pick_id,
            'pay_time' => $activityAR->pay_datetime,
            'custom_user' => $customUser->account,
        ];
    }

    public function exchange(){
        if(Yii::$app->BusinessUser->account->area->level != Area::LEVEL_QUATERNARY){
            $this->addError('exchangeQuery', 13251);
            return false;
        }
        $queryResult = $this->exchangeQuery();
        if(!$queryResult['status']){
            $this->addError('exchange', 13252);
            return false;
        }
        if(ApiForBusiness::exchange($this->pick_id, Yii::$app->BusinessUser->account->area->id, false)){
            return true;
        }else{
            $this->addError('exchange', 13252);
            return false;
        }
    }
}
