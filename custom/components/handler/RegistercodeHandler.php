<?php
namespace custom\components\handler;

use Yii;
use common\components\handler\Handler;
use common\ActiveRecord\CustomUserRegistercodeAR;
use business\models\parts\Area;
use common\models\parts\custom\CustomUser;
use custom\models\parts\RegisterCode;

class RegistercodeHandler extends Handler{

    public static function createPartnerCode(int $quantity, Area $area = null, $return = 'throw'){
        return self::create($quantity, is_null($area) ? new Area(['id' => Area::DEFAULT_FIFTH_ID]) : $area, RegisterCode::LEVEL_PARTNER, true, $return);
    }

    public static function createInSystemCode(int $quantity, Area $area, $return = 'throw'){
        return self::create($quantity, $area, RegisterCode::LEVEL_IN_SYSTEM, true, $return);
    }

    public static function create(int $quantity, Area $area, $level, bool $authorized, $return = 'throw'){
        if($quantity < 1)return Yii::$app->EC->callback($return, 'P_int');
        if($area->level->hasChild)return Yii::$app->EC->callback($return, 'this area can not create register code');
        if(!in_array($level, CustomUser::getLevels()))return Yii::$app->EC->callback($return, 'unavailable code level');
        $authorized = $authorized ? 1 : 0;
        $registerCode = [];
        for($i = 0; $i < $quantity; $i++){
            do{
                $account = rand(100000000, 999999999);
            }while(CustomUserRegistercodeAR::findOne(['account' => $account]) || in_array($account, $registerCode));
            $registerCode[] = $account;
        }
        $quaternaryArea = $area->parent;
        $tertiaryArea = $quaternaryArea->parent;
        $secondaryArea = $tertiaryArea->parent;
        $topArea = $secondaryArea->parent;
        foreach($registerCode as $key => $account){
            $registerCode[$key] = [
                $account, //account
                $area->id, //business_area_id
                $quaternaryArea->id, //business_quaternary_area_id
                $tertiaryArea->id, //business_tertiary_area_id
                $secondaryArea->id, //business_secondary_area_id
                $topArea->id, //business_top_area_id
                $level, //level
                $authorized, //authorized
                Yii::$app->time->fullDate, //create_time
                Yii::$app->time->unixTime, //create_unixtime
                '0000-01-01 00:00:00', //register_time
            ];
        }
        try{
            Yii::$app->RQ->AR(new CustomUserRegistercodeAR)->batchInsert([
                'account',
                'business_area_id',
                'business_quaternary_area_id',
                'business_tertiary_area_id',
                'business_secondary_area_id',
                'business_top_area_id',
                'level',
                'authorized',
                'create_time',
                'create_unixtime',
                'register_time',
            ], $registerCode);
        }catch(\Exception $e){
            return Yii::$app->EC->callback($return, $e);
        }
        $obj = [];
        foreach($registerCode as $one){
            $obj[] = new RegisterCode(['account' => $one]);
        }
        return $obj;
    }
}
