<?php
namespace common\models\parts\partner;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;
use common\ActiveRecord\CustomUserAuthorizationDataAR;
use common\models\parts\district\District;
use common\models\parts\OSSImage;

class AuthorizeData extends Object{

    public $id;
    public $authorization;
    private $_authorization;

    protected $AR;

    public function init(){
        if(!is_null($this->id)){
            if(!$this->AR = CustomUserAuthorizationDataAR::findOne($this->id))throw new InvalidConfigException('unavailable id');
        }elseif(!is_null($this->authorization)){
            if(!$this->AR = CustomUserAuthorizationDataAR::find()->where(['custom_user_authorization_id' => $this->authorization])->orderBy(['id' => SORT_DESC])->one())throw new InvalidConfigException('unavailable authorization');
        }else{
            throw new InvalidConfigException('unavailable config');
        }
        $this->id = $this->AR->id;
        $this->authorization = $this->AR->custom_user_authorization_id;
    }

    public function getAuthorization(){
        if(is_null($this->_authorization)){
            $this->_authorization = new Authorization(['id' => $this->AR->custom_user_authorization_id]);
        }
        return $this->_authorization;
    }

    public function getStoreName(){
        return $this->AR->store_name;
    }

    public function getStoreFront(){
        if($this->AR->store_front){
            return new OSSImage(['images' => $this->AR->store_front]);
        }else{
            return false;
        }
    }

    public function getStoreInside(){
        if($this->AR->store_inside){
            return new OSSImage(['images' => $this->AR->store_inside]);
        }else{
            return false;
        }
    }

    public function getCorpName(){
        return $this->AR->corp_name;
    }

    public function getDistrict(){
        return new District([
            'districtId' => $this->AR->district_district_id,
            'cityId' => $this->AR->district_city_id,
            'provinceId' => $this->AR->district_province_id,
        ]);
    }

    public function getAddress(){
        return $this->AR->address;
    }

    public function getManagerName(){
        return $this->AR->manager_name;
    }

    public function getManagerIdcardFront(){
        if($this->AR->manager_idcard_front){
            return new OSSImage(['images' => $this->AR->manager_idcard_front]);
        }else{
            return false;
        }
    }

    public function getManagerIdcardBack(){
        if($this->AR->manager_idcard_back){
            return new OSSImage(['images' => $this->AR->manager_idcard_back]);
        }else{
            return false;
        }
    }

    public function getContactName(){
        return $this->AR->contact_name;
    }

    public function getContactMobile(){
        return $this->AR->contact_mobile;
    }

    public function getEmail(){
        return $this->AR->email;
    }

    public function getBusinessLicence(){
        if($this->AR->business_licence){
            return new OSSImage(['images' => $this->AR->business_licence]);
        }else{
            return false;
        }
    }

    public function getAuthorizeComment(){
        return $this->AR->authorize_comment;
    }

    public function setAuthorizeComment(string $comment, $return = 'throw'){
        return Yii::$app->RQ->AR($this->AR)->update([
            'authorize_comment' => $comment,
        ], $return);
    }
}
