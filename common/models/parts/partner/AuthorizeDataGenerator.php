<?php
namespace common\models\parts\partner;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;
use yii\base\InvalidCallException;
use common\models\parts\district\District;
use common\models\parts\OSSImage;
use common\ActiveRecord\CustomUserAuthorizationAR;
use common\ActiveRecord\CustomUserAuthorizationDataAR;

class AuthorizeDataGenerator extends Object{

    public $authorization;
    private $_authorization;

    private $_storeName;
    private $_storeFront;
    private $_storeInside;
    private $_corpName;
    private $_email;
    private $_district;
    private $_address;
    private $_managerName;
    private $_managerIdcardFront;
    private $_managerIdcardBack;
    private $_contactName;
    private $_contactMobile;
    private $_businessLicence;

    public function init(){
        if($this->authorization instanceof Authorization){
            $this->_authorization = $this->authorization;
        }else{
            throw new InvalidConfigException('unavailable authorization obj');
        }
    }

    public function getAuthorization(){
        return $this->_authorization;
    }

    public function build(bool $setToApply = true, $return = 'throw'){
        if(is_null($this->_storeName) ||
            is_null($this->_storeFront) ||
            is_null($this->_storeInside) ||
            is_null($this->_corpName) ||
            is_null($this->_email) ||
            is_null($this->_district) ||
            is_null($this->_address) ||
            is_null($this->_managerName) ||
            is_null($this->_managerIdcardFront) ||
            is_null($this->_managerIdcardBack) ||
            is_null($this->_contactName) ||
            is_null($this->_contactMobile) ||
            is_null($this->_businessLicence)
        )throw new InvalidCallException('one or more params missing');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $dataId = Yii::$app->RQ->AR(new CustomUserAuthorizationDataAR)->insert([
                'custom_user_authorization_id' => $this->_authorization->id,
                'store_name' => $this->_storeName,
                'store_front' => $this->_storeFront,
                'store_inside' => $this->_storeInside,
                'corp_name' => $this->_corpName,
                'district_province_id' => $this->_district->province->provinceId,
                'district_city_id' => $this->_district->city->cityId,
                'district_district_id' => $this->_district->districtId,
                'address' => $this->_address,
                'manager_name' => $this->_managerName,
                'manager_idcard_front' => $this->_managerIdcardFront,
                'manager_idcard_back' => $this->_managerIdcardBack,
                'contact_name' => $this->_contactName,
                'contact_mobile' => $this->_contactMobile,
                'email' => $this->_email,
                'business_licence' => $this->_businessLicence,
            ]);
            Yii::$app->RQ->AR(CustomUserAuthorizationAR::findOne($this->_authorization->id))->update([
                'custom_user_authorization_data_id' => $dataId,
            ]);
            if($setToApply){
                $this->_authorization->init();
                $this->_authorization->setStatus(Authorization::STATUS_AUTHORIZE_APPLY);
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public function addStore(string $name, OSSImage $storeFront = null, OSSImage $storeInside = null){
        $storeNameLength = mb_strlen($name,Yii::$app->charset);
        if($storeNameLength > 0 && $storeNameLength <= 255){
            $this->_storeName = $name;
            $this->_storeFront = is_null($storeFront) ? 0 : $this->getOSSImageId($storeFront);
            $this->_storeInside = is_null($storeInside) ? 0 : $this->getOSSImageId($storeInside);
            return $this;
        }else{
            throw new InvalidCallException('unavailable store name');
        }
    }

    public function addCorpName(string $corpName = null){
        if(is_null($corpName)){
            $this->_corpName = '';
        }else{
            $corpNameLength = mb_strlen($corpName,Yii::$app->charset);
            if($corpNameLength > 0 && $corpNameLength <= 255){
                $this->_corpName = $corpName;
            }else{
                throw new InvalidCallException('unavailable corporation name');
            }
        }
        return $this;
    }

    public function addEmail(string $email){
        $emailLength = strlen($email);
        if($emailLength > 0 && $emailLength <= 255){
            $this->_email = $email;
            return $this;
        }else{
            throw new InvalidCallException('unavailable email');
        }
    }

    public function addAddress(District $district, string $address){
        $addressLength = mb_strlen($address,Yii::$app->charset);
        if($addressLength <= 0 || $addressLength > 255)throw new InvalidCallException('unavailable address');
        try{
            if(!$district->validate())throw new \Exception;
            $district->city;
            $district->province;
        }catch(\Exception $e){
            throw $e;
        }
        $this->_district = $district;
        $this->_address = $address;
        return $this;
    }

    public function addManager(string $name, OSSImage $idcardFront = null, OSSImage $idcardBack = null){
        $nameLength = mb_strlen($name,Yii::$app->charset);
        if($nameLength <= 0 || $nameLength > 10)throw new InvalidCallException('unavailable name');
        $this->_managerName = $name;
        $this->_managerIdcardFront = is_null($idcardFront) ? 0 : $this->getOSSImageId($idcardFront);
        $this->_managerIdcardBack = is_null($idcardBack) ? 0 : $this->getOSSImageId($idcardBack);
        return $this;
    }

    public function addContact(string $name = null, string $mobile = null){
        if(is_null($name)){
            $name = '';
        }else{
            $nameLength = mb_strlen($name,Yii::$app->charset);
            if($nameLength <= 0 || $nameLength > 10)throw new InvalidCallException('unavailable contact name');
        }
        if(is_null($mobile)){
            $mobile = 0;
        }else{
            $mobileLength = strlen($mobile);
            if(!is_numeric($mobile) || $mobileLength != 11)throw new InvalidCallExcpetion('unavailable contact mobile');
        }
        $this->_contactName = $name;
        $this->_contactMobile = $mobile;
        return $this;
    }

    public function addBusinessLicence(OSSImage $licence = null){
        if(is_null($licence)){
            $this->_businessLicence = 0;
        }else{
            $this->_businessLicence = $this->getOSSImageId($licence);
        }
        return $this;
    }

    protected function getOSSImageId(OSSImage $image){
        $id = $image->id;
        if(is_numeric($id)){
            return $id;
        }elseif(is_array($id)){
            if(count($id) != 1){
                return null;
            }else{
                return current($id);
            }
        }else{
            return null;
        }
    }
}
