<?php
namespace custom\models\parts;

use Yii;
use yii\base\Object;
use common\ActiveRecord\CustomUserRegistercodeAR;
use yii\base\InvalidConfigException;
use business\models\parts\Area;
use common\models\parts\custom\CustomUser;
use common\models\parts\partner\PartnerApply;
use common\ActiveRecord\PartnerApplyAR;

class RegisterCode extends Object{

    const STATUS_UNUSED = 0;
    const STATUS_USED = 1;
    const STATUS_UNAVAILABLE = 0;
    const STATUS_AVAILABLE = 1;

    const LEVEL_PARTNER = CustomUser::LEVEL_PARTNER;
    const LEVEL_IN_SYSTEM = CustomUser::LEVEL_IN_SYSTEM;
    const LEVEL_COMPANY = CustomUser::LEVEL_COMPANY;

    public $id;
    public $account;

    protected $AR;

    public function init(){
        if($this->id){
            $condition = ['id' => $this->id];
        }elseif($this->account){
            $condition = ['account' => $this->account];
        }else{
            $condition = false;
        }
        if($condition){
            if(!$this->AR = CustomUserRegistercodeAR::find()->where($condition)->one())throw new InvalidConfigException;
        }else{
            throw new InvalidConfigException;
        }
        $this->id = $this->AR->id;
        $this->account = $this->AR->account;
    }

    public function getRequireAuthorize(){
        return $this->AR->authorized ? false : true;
    }

    public function getLevel(){
        return $this->AR->level;
    }

    public function getAccount(){
        return $this->AR->account;
    }

    public function getArea(){
        return new Area(['id' => $this->AR->business_area_id]);
    }

    public function getIsUsed(){
        return $this->AR->used ? true : false;
    }

    public function getCreateTime($unixTime = false){
        return $unixTime ? $this->AR->create_unixtime : $this->AR->create_time;
    }

    public function getRegisterTime($unixTime = false){
        return $unixTime ? $this->AR->register_unixtime : $this->AR->register_time;
    }

    public function setUsed($confirm = false, $return = 'throw'){
        if($confirm){
            return Yii::$app->RQ->AR($this->AR)->update([
                'used' => self::STATUS_USED,
                'register_time' => Yii::$app->time->fullDate,
                'register_unixtime' => Yii::$app->time->unixTime,
            ], $return);
        }else{
            return Yii::$app->EC->callback($return, 'you must to confirm the action');
        }
    }

    public function getPartnerApply(){
        if($this->getLevel() == self::LEVEL_PARTNER){
            $applyId = PartnerApplyAR::findOne(['custom_user_registercode_id' => $this->id])->id;
            return new PartnerApply(['id' => $applyId]);
        }else{
            return false;
        }
    }

    /**
     *====================================================
     * 设置不可用
     * @param string $return
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function setUnAvailable($return = 'throw'){
        return Yii::$app->RQ->AR($this->AR)->update(['is_available' =>self::STATUS_UNAVAILABLE], $return);
    }
}
