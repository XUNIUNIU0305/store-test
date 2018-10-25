<?php
namespace custom\components\handler;

use business\models\parts\Area;
use common\ActiveRecord\BusinessAreaAR;
use Yii;
use common\components\handler\Handler;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\CustomUserWalletAR;
use custom\models\parts\RegisterCode;
use common\models\parts\district\District;
use common\models\parts\business_area\QuaternaryArea;
use custom\models\parts\UserIdentity;
use common\models\parts\partner\PartnerApply;
use common\ActiveRecord\CustomUserAuthorizationAR;
use common\models\parts\partner\Authorization;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\db\Exception;

class AccountHandler extends Handler{

    const DEFAULT_EXPIRE_DATETIME = '2099-12-31 23:59:59';
    const DEFAULT_EXPIRE_UNIXTIME = 4102415999;

    public static function create(array $attributes, $return = 'throw'){
        $account = null;
        $passwd = null;
        $district = null;
        $mobile = null;
        $email = null;
        extract($attributes, EXTR_IF_EXISTS);
        if(!($account instanceof RegisterCode) ||
            $account->isUsed ||
            empty($passwd) ||
            !($district instanceof District) ||
            !is_numeric($mobile) ||
            empty($email)
        )return Yii::$app->EC->callback($return, 'attributes error');

        try {
            $business_quaternary_area_id = $account->area->parent->id;
            $business_tertiary_area_id = $account->area->parent->parent->id;
            $business_secondary_area_id = $account->area->parent->parent->parent->id;
            $business_top_area_id = $account->area->parent->parent->parent->parent->id;

            if (!isset($business_quaternary_area_id) || !isset($business_tertiary_area_id)
                || !isset($business_secondary_area_id) || !isset($business_top_area_id)) {
                throw new InvalidConfigException();
            }
        } catch (InvalidConfigException $e) {
            return Yii::$app->EC->callback($return, 'five level business_area_id error');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try{
            $accountId = Yii::$app->RQ->AR(new CustomUserAR)->insert([
                'account' => $account->account,
                'passwd' => Yii::$app->security->generatePasswordHash($passwd),
                'level' => $account->level,
                'authorized' => $account->requireAuthorize ? 0 : 1,
                'expire_datetime' => self::DEFAULT_EXPIRE_DATETIME,
                'expire_unixtime' => self::DEFAULT_EXPIRE_UNIXTIME,
                'business_area_id' => $account->area->id,
                'business_quaternary_area_id' => $business_quaternary_area_id,
                'business_tertiary_area_id' => $business_tertiary_area_id,
                'business_secondary_area_id' => $business_secondary_area_id,
                'business_top_area_id' => $business_top_area_id,
                'district_province_id' => $district->province->provinceId,
                'district_city_id' => $district->city->cityId,
                'district_district_id' => $district->districtId,
                'mobile' => $mobile,
                'email' =>$email,
                'header_img'=> Yii::$app->params['OSS_PostHost'] . '/a/avatar/default_avatar.jpg',
                'shop_name'=>'',
                'nick_name'=>'',
            ]);
            Yii::$app->RQ->AR(new CustomUserWalletAR)->insert([
                'custom_user_id' => $accountId,
                'rmb' => 0,
            ]);
            $account->used = true;

            //邀请门店补填信息
            //if($account->level == $account::LEVEL_PARTNER){
                //self::createAuthorization($account->partnerApply, $accountId);
            //}

            $transaction->commit();
            return UserIdentity::findOne($accountId);
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, 'create account failed');
        }
    }

    public static function createAuthorization(PartnerApply $apply, $accountId, $return = 'throw'){
        $partnerPromoter = $apply->partnerPromoter;
        $authorizationId = Yii::$app->RQ->AR(new CustomUserAuthorizationAR)->insert([
            'custom_user_id' => $accountId,
            'custom_user_account' => $apply->registerCode->account,
            'partner_apply_id' => $apply->id,
            'mobile' => $apply->mobile,
            'partner_promoter_id' => $partnerPromoter->id,
            'promoter_type' => $partnerPromoter->type,
            'promoter_user_id' => $partnerPromoter->user->id,
            'award_rmb' => $apply->awardRmb,
            'status' => Authorization::STATUS_PAID,
            'pay_datetime' => $apply->getPayTime(false),
            'pay_unixtime' => $apply->getPayTime(true),
        ]);
        return new Authorization(['id' => $authorizationId]);
    }
}
