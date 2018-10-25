<?php
namespace common\models\parts\partner;

use Yii;
use yii\base\Object;
use common\ActiveRecord\PartnerPromoterAR;
use yii\base\InvalidConfigException;
use business\models\parts\Account;
use common\models\parts\custom\CustomUser;
use business\models\parts\Role;

class PartnerPromoter extends Object{

    public $id;

    protected $AR;

    const TYPE_BUSINESS = 1;
    const TYPE_CUSTOM = 2;

    public function init(){
        if(!$this->AR = PartnerPromoterAR::findOne($this->id))throw new InvalidConfigException('unavailable id');
    }

    public function getTitle(){
        return $this->AR->title;
    }

    public function getType(){
        return (int)$this->AR->type;
    }

    public function getUser(){
        switch($this->getType()){
            case self::TYPE_BUSINESS:
                return new Account(['id' => $this->AR->business_user_id]);

            case self::TYPE_CUSTOM:
                return new CustomUser(['id' => $this->AR->custom_user_id]);

            default:
                return false;
        }
    }

    public function getAwardRmb(){
        return (float)$this->AR->award_rmb;
    }

    public function getIsAvailable(){
        if(!$this->AR->is_available)return false;
        switch($this->getType()){
            case self::TYPE_BUSINESS:
                $account = $this->getUser();
                if($account->status == $account::STATUS_NORMAL &&
                    $account->role->id == Role::QUATERNARY
                ){
                    return true;
                }else{
                    return false;
                }
                break;

            case self::TYPE_CUSTOM:
                return $this->getUser()->isAvailable;
                break;

            default:
                return false;
        }
    }
}
