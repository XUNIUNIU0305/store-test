<?php
namespace admin\components\handler;

use Yii;
use common\components\handler\Handler;
use common\ActiveRecord\PartnerApplyAR;
use common\models\parts\partner\PartnerApply;
use common\models\parts\partner\PartnerPromoter;

class PartnerApplyHandler extends Handler{

    public static function create(PartnerPromoter $promoter, $data, $return = 'throw'){
        if(!$promoter->isAvailable)return Yii::$app->EC->callback($return, 'this promoter is unavailable');
        if(is_numeric($data)){
            $mobile = $data;
            $passwd = null;
        }elseif(is_array($data)){
            $mobile = $data['mobile'] ?? null;
            if(!isset($data['passwd']) || !is_string($data['passwd']))return Yii::$app->EC->callback($return, 'password is unavailable');
            $passwd = $data['passwd'];
        }else{
            return Yii::$app->EC->callback($return, 'unknown data');
        }
        if(!is_numeric($mobile) || $mobile > 19999999999 || $mobile < 10000000000)return Yii::$app->EC->callback($return, 'unavailable mobile');
        if(is_null($passwd)){
            $passwd = '';
        }else{
            $passwd = Yii::$app->security->generatePasswordHash($passwd);
        }
        try{
            $applyId = Yii::$app->RQ->AR(new PartnerApplyAR)->insert([
                'partner_promoter_id' => $promoter->id,
                'mobile' => $mobile,
                'passwd' => $passwd,
                'award_rmb' => PartnerApply::AWARD_RMB,
                'create_datetime' => Yii::$app->time->fullDate,
                'create_unixtime' => Yii::$app->time->unixTime,
            ]);
        }catch(\Exception $e){
            return Yii::$app->EC->callback($return, $e);
        }
        return new PartnerApply(['id' => $applyId]);
    }
}
