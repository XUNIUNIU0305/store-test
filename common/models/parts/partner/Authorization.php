<?php
namespace common\models\parts\partner;

use common\ActiveRecord\AdminRechargeApplyAR;
use common\ActiveRecord\AdminRechargeLogAR;
use common\ActiveRecord\AdminTradePartnerAR;
use common\ActiveRecord\AlipayNotifyLogAR;
use common\ActiveRecord\WxpayNotifyLogAR;
use common\models\parts\trade\recharge\RechargeApply;
use custom\models\parts\trade\RechargeLog;
use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;
use common\ActiveRecord\CustomUserAuthorizationAR;
use common\models\parts\custom\CustomUser;
use yii\base\InvalidCallException;
use common\ActiveRecord\CustomUserAR;

class Authorization extends Object{

    const STATUS_PAID = 1;
    const STATUS_AUTHORIZE_APPLY = 2;
    const STATUS_AUTHORIZE_FAIL = 3;
    const STATUS_AUTHORIZE_SUCCESS = 4;
    const STATUS_ACCOUNT_VALID = 5;

    const VALID_SECONDS = 31536000;

    public $id;
    public $account;
    public $userId;

    protected $AR;

    public function init(){
        if(!is_null($this->id)){
            $fieldName = 'id';
            $fieldValue = $this->id;
        }elseif(!is_null($this->account)){
            $fieldName = 'custom_user_account';
            $fieldValue = $this->account;
        }elseif(!is_null($this->userId)){
            $fieldName = 'custom_user_id';
            $fieldValue = $this->userId;
        }else{
            throw new InvalidConfigException('config missing');
        }
        if(!$this->AR = CustomUserAuthorizationAR::findOne([$fieldName => $fieldValue]))throw new InvalidConfigException('unavailable config');
        $this->id = $this->AR->id;
        $this->account = $this->AR->custom_user_account;
        $this->userId = $this->AR->custom_user_id;
    }

    public function getCustomUser(){
        return new CustomUser(['id' => $this->AR->custom_user_id]);
    }

    public function getMobile(){
        return $this->AR->mobile;
    }

    public function getPartnerApply(){
        return new PartnerApply(['id' => $this->AR->partner_apply_id]);
    }

    public function getPromoter(){
        return new PartnerPromoter(['id' => $this->AR->partner_promoter_id]);
    }

    public function getPromoterType(){
        return $this->AR->promoter_type;
    }

    public function getPromoterUserId(){
        return $this->AR->promoter_user_id;
    }

    public function getAwardRmb(){
        return (float)$this->AR->award_rmb;
    }

    public function getStatus(){
        return $this->AR->status;
    }

    public static function getStatuses(){
        return [
            self::STATUS_PAID,
            self::STATUS_AUTHORIZE_APPLY,
            self::STATUS_AUTHORIZE_FAIL,
            self::STATUS_AUTHORIZE_SUCCESS,
            self::STATUS_ACCOUNT_VALID,
        ];
    }

    public function getPayTime(bool $unixTime = false){
        return $unixTime ? $this->AR->pay_unixtime : $this->AR->pay_datetime;
    }

    public function getAuthorizeApplyTime(bool $unixTime = false){
        if($unixTime){
            return $this->AR->authorize_apply_unixtime ? : false;
        }else{
            if($this->AR->authorize_apply_datetime == '0000-01-01 00:00:00'){
                return false;
            }else{
                return $this->AR->authorize_apply_datetime;
            }
        }
    }

    public function getAuthorizedTime(bool $unixTime = false){
        if($unixTime){
            return $this->AR->authorized_unixtime ? : false;
        }else{
            if($this->AR->authorized_datetime == '0000-01-01 00:00:00'){
                return false;
            }else{
                return $this->AR->authorized_datetime;
            }
        }
    }

    public function getAccountValidTime(bool $unixTime = false){
        if($unixTime){
            return $this->AR->account_valid_unixtime ? : false;
        }else{
            if($this->AR->account_valid_datetime == '0000-01-01 00:00:00'){
                return false;
            }else{
                return $this->AR->account_valid_datetime;
            }
        }
    }

    public function getAuthorizeData(){
        if($dataId = $this->AR->custom_user_authorization_data_id){
            return new AuthorizeData(['id' => $this->AR->custom_user_authorization_data_id]);
        }else{
            return false;
        }
    }

    public function setStatus(int $status, $return = 'throw'){
        switch($status){
            case self::STATUS_AUTHORIZE_APPLY:
                if(!$this->getAuthorizeData())return Yii::$app->EC->callback($return, 'authorize data missing');
                if($this->getStatus() != self::STATUS_PAID && $this->getStatus() != self::STATUS_AUTHORIZE_FAIL)return Yii::$app->EC->callback($return, 'current status can not be set to this status');
                return Yii::$app->RQ->AR($this->AR)->update([
                    'status' => self::STATUS_AUTHORIZE_APPLY,
                    'authorize_apply_datetime' => Yii::$app->time->fullDate,
                    'authorize_apply_unixtime' => Yii::$app->time->unixTime,
                ], $return);

            case self::STATUS_AUTHORIZE_FAIL:
                if($this->getStatus() != self::STATUS_AUTHORIZE_APPLY)return Yii::$app->EC->callback($return, 'current status can not be set to this status');
                return Yii::$app->RQ->AR($this->AR)->update([
                    'status' => self::STATUS_AUTHORIZE_FAIL,
                    'authorized_datetime' => Yii::$app->time->fullDate,
                    'authorized_unixtime' => Yii::$app->time->unixTime,
                ], $return);

            case self::STATUS_AUTHORIZE_SUCCESS:
                if($this->getStatus() != self::STATUS_AUTHORIZE_APPLY)return Yii::$app->EC->callback($return, 'current status can not be set to this status');
                $transaction = Yii::$app->db->beginTransaction();
                try{
                    Yii::$app->RQ->AR($this->AR)->update([
                        'status' => self::STATUS_AUTHORIZE_SUCCESS,
                        'authorized_datetime' => Yii::$app->time->fullDate,
                        'authorized_unixtime' => Yii::$app->time->unixTime,
                    ]);
                    $authorizeData = $this->getAuthorizeData();
                    $district = $authorizeData->district;
                    Yii::$app->RQ->AR(CustomUserAR::findOne($this->AR->custom_user_id))->update([
                        'district_district_id' => $district->districtId,
                        'district_city_id' => $district->city->cityId,
                        'district_province_id' => $district->province->provinceId,
                        'email' => $authorizeData->email,
                    ]);
                    $transaction->commit();
                    return 1;
                }catch(\Exception $e){
                    $transaction->rollBack();
                    return Yii::$app->EC->callback($return, $e);
                }

            case self::STATUS_ACCOUNT_VALID:
                if($this->getStatus() != self::STATUS_AUTHORIZE_SUCCESS)return Yii::$app->EC->callback($return, 'current status can not be set to this status');
                $transaction = Yii::$app->db->beginTransaction();
                try{
                    Yii::$app->RQ->AR($this->AR)->update([
                        'status' => self::STATUS_ACCOUNT_VALID,
                        'account_valid_datetime' => Yii::$app->time->fullDate,
                        'account_valid_unixtime' => Yii::$app->time->unixTime,
                    ]);
                    $this->getCustomUser()->setAuthorized(self::VALID_SECONDS);
                    $transaction->commit();
                    return true;
                }catch(\Exception $e){
                    $transaction->rollBack();
                    return Yii::$app->EC->callback($return, $e);
                }

            default:
                throw new InvalidCallException('unavailable status');
        }
    }

    public function newAuthorizeData(){
        if($this->getStatus() == self::STATUS_PAID || $this->getStatus() == self::STATUS_AUTHORIZE_FAIL){
            return new AuthorizeDataGenerator([
                'authorization' => $this,
            ]);
        }else{
            throw new InvalidCallException('unable to generate new authorize data');
        }
    }


    /**
     *====================================================
     * 获取被邀请审核信息
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function getBeInvited(){
       return Yii::$app->RQ->AR(new CustomUserAuthorizationAR())->scalar([
           'select'=>['id'],
           'where'=>['promoter_user_id'=>$this->AR->custom_user_id]
       ]);
    }
}
