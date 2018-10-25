<?php
/**
 * User: YuPeiKun
 * Date: 2018/8/24
 * Time: 11:56
 * Desc:
 */

namespace mobile\modules\member\models;

use common\ActiveRecord\ActivityGpubsAddressAR;
use Yii;
use common\models\Model;
use mobile\components\handler\SpotAddressHandler;
use common\models\parts\gpubs\GpubsAddress;

class SpotAddressModel extends  Model
{
    const SCE_LIST_GPUBS_ADDRESS = 'list_address'; //自提点地址列表
    const SCE_GET_DEFAULT_ADDRESS="get_default_address";//获取自提点默认地址
    const SCE_GET_GPUBS_ADDRESS="get_edit_address";//获取自提点地址(编辑)

    const SCE_SET_DEFAULT = 'set_default'; //设置默认自提点
    const SCE_ADD_GPUBS_ADDRESS = 'add_gpubs_address'; //添加自提点
    const SCE_EDIT_GPUBS_ADDRESS = 'edit_gpubs_address'; //修改自提点
    const SCE_DELETE_GPUBS_ADDRESS="delete_address";//删除自提点

    public $id;
    public $spot_name;
    public $province;
    public $city;
    public $district;
    public $detailed_address;
    public $consignee;
    public $mobile;
    public $postal_code;
    public $default;

    public function scenarios()
    {
        return [
            self::SCE_LIST_GPUBS_ADDRESS=>[],
            self::SCE_GET_DEFAULT_ADDRESS=>[],
            self::SCE_GET_GPUBS_ADDRESS=>['id'],
            self::SCE_SET_DEFAULT => ['id'],
            self::SCE_DELETE_GPUBS_ADDRESS => ['id'],
            self::SCE_ADD_GPUBS_ADDRESS => [
                'spot_name',
                'province',
                'city',
                'district',
                'detailed_address',
                'consignee',
                'mobile',
                'postal_code',
                'default'
            ],
            self::SCE_EDIT_GPUBS_ADDRESS => [
                'id',
                'spot_name',
                'province',
                'city',
                'district',
                'detailed_address',
                'consignee',
                'mobile',
                'postal_code',
                'default'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['id'],
                'exist',
                'targetClass'=>ActivityGpubsAddressAR::className(),
                'filter'=>['custom_user_id'=>Yii::$app->user->id],
                'message'=>9001,
            ],
            [
                ['city', 'district'],
                'default',
                'value' => 0,
            ],
            [
                ['spot_name','consignee', 'province', 'city', 'district', 'detailed_address', 'mobile', 'postal_code', 'id'],
                'required',
                'message' => 9001,
            ],
            [
                ['consignee'],
                'string',
                'length' => [1, 30],
                'message' => 10134,
                'tooLong' => 10134,
                'tooShort' => 10134,
            ],
            [
                ['district'],
                'common\validators\district\DistrictValidator',
                'province' => $this->province,
                'city' => $this->city,
                'message' => 3063,
            ],
            [
                ['detailed_address'],
                'string',
                'length' => [1, 200],
                'message' => 3062,
                'tooShort' => 3062,
                'tooLong' => 3062,
            ],
            [
                ['spot_name'],
                'string',
                'length' => [1, 200],
                'message' => 10130,
                'tooShort' => 10130,
                'tooLong' => 10130,
            ],
            [
                ['mobile'],
                'integer',
                'min' => 10000000000,
                'max' => 19999999999,
                'message' => 3065,
                'tooSmall' => 3065,
                'tooBig' => 3065,
            ],
            [
                ['postal_code'],
                'match',
                'pattern' => '/^[0-9]{6}$/',
                'message' => 3064,
            ],
        ];
    }

    //自提点地址列表
    public function listAddress(){
        try{
            $addresses = SpotAddressHandler::getList(true,Yii::$app->user->id);
            $list = [];
            foreach($addresses as $address){
                $list[] = self::getReturnAddress($address);
            }
            return $list;
        }catch (\Exception $exception){
            return [];
        }
    }

    //获取自提点默认地址
    public function getDefaultAddress(){
        $address =  SpotAddressHandler::getDefaultAddress(Yii::$app->user->id);
        if ($address){
            return self::getReturnAddress($address);
        }else{
            return [];
        }
    }

    //获取自提点地址详情(编辑)
    public function getEditAddress(){
        if ($this->id){
            $address = SpotAddressHandler::getList($this->id , Yii::$app->user->id);
            if ($address){
                return self::getReturnAddress($address);
            }
        }
        $this->addError('getEditAddress',3092);
        return false;

    }

    //删除自提点
    public function deleteAddress(){
        $transaction = Yii::$app->db->beginTransaction();
        $address = new GpubsAddress(['id' => $this->id]);
        if(SpotAddressHandler::remove($address)){
            if ($address->IsDefault){
                if($_address = ActivityGpubsAddressAR::findOne(['custom_user_id' => Yii::$app->user->id])){
                    if(!SpotAddressHandler::setDefault(new GpubsAddress(['id' => $_address->id]))){
                        $transaction->rollBack();
                        $this->addError('deleteAddress',3072);
                        return false;
                    }
                }
            }
            $transaction->commit();
            return true;
        }
        $transaction->rollBack();
        $this->addError('deleteAddress',3072);
        return false;
    }

    //添加自提点
    public function addGpubsAddress(){
        $transaction = Yii::$app->db->beginTransaction();
        $address = SpotAddressHandler::create([
            'custom_user_id' => Yii::$app->user->id,
            'spot_name' => $this->spot_name,
            'consignee' => $this->consignee,
            'district_province_id' => $this->province,
            'district_city_id' => $this->city,
            'district_district_id' => $this->district,
            'detailed_address' => $this->detailed_address,
            'mobile' => $this->mobile,
            'postal_code' => $this->postal_code,
        ]);
        if($address){
            if($this->default){
                if(SpotAddressHandler::setDefault($address)){
                    $transaction->commit();
                    return true;
                }else{
                    $transaction->rollBack();
                    return false;
                }
            }elseif(!(ActivityGpubsAddressAR::findOne(['custom_user_id' => Yii::$app->user->id, 'default' => ActivityGpubsAddressAR::DEFAULT_ADDRESS]))){
                if(SpotAddressHandler::setDefault($address)){
                    $transaction->commit();
                    return true;
                }
            }else{
                $transaction->commit();
                return true;
            }
        }
        $transaction->rollBack();
        $this->addError('addGpubsAddress',3091);
        return false;
    }

    //修改自提点
    public function editGpubsAddress(){
        $transaction = Yii::$app->db->beginTransaction();
        $address = new GpubsAddress(['id' => $this->id]);
        $attributes = array_filter([
            'spot_name' => $this->spot_name,
            'consignee' => $this->consignee,
            'district_province_id' => $this->province,
            'district_city_id' => $this->city,
            'district_district_id' => $this->district,
            'detailed_address' => $this->detailed_address,
            'mobile' => $this->mobile,
            'postal_code' => $this->postal_code,
        ], function($v){
            return (!is_null($v) && $v !== false && $v !== '');
        });
        if($address->set($attributes) !== false){
            if($this->default){
                if(SpotAddressHandler::setDefault($address)){
                    $transaction->commit();
                    return true;
                }
            }else{
                $transaction->commit();
                return true;
            }
        }
        $transaction->rollBack();
        $this->addError('editGpubsAddress',3091);
        return false;
    }

    //设置当前自提点为默认自提点
    public function setDefault(){
        if (SpotAddressHandler::setDefault(new GpubsAddress(['id' => $this->id]))){
            return true;
        }
        $this->addError('setDefault',3081);
        return false;
    }

    //获取返回地址信息
    public function getReturnAddress($address){
        return [
            'id' => $address->id,
            'spot_name' => $address->spotName,
            'province' => [
                'id' => $address->province,
                'name' => $address->getProvince(true),
            ],
            'city' => [
                'id' => $address->city,
                'name' => $address->getCity(true),
            ],
            'district' => [
                'id' => $address->district,
                'name' => $address->getDistrict(true),
            ],
            'detailed_address' => $address->detail,
            'consignee' => $address->consignee,
            'mobile' => $address->mobile,
            'postal_code' => $address->postalCode,
            'default' => $address->isDefault,
        ];
    }

}