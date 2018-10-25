<?php
namespace custom\modules\account\models;

use Yii;
use common\models\Model;
use custom\components\handler\AddressHandler;
use common\models\parts\Address;
use common\ActiveRecord\CustomUserAddressAR;

class AddressModel extends Model{

    const SCE_ADD_ADDRESS = 'add_address';
    const SCE_REMOVE_ADDRESS = 'remove_address';
    const SCE_SET_DEFAULT = 'set_default';
    const SCE_EDIT_ADDRESS = 'edit_address';

    public $consignee;
    public $province;
    public $city;
    public $district;
    public $detail;
    public $mobile;
    public $postal_code;
    public $id;

    public function scenarios(){
        return [
            self::SCE_ADD_ADDRESS => [
                'consignee',
                'province',
                'city',
                'district',
                'detail',
                'mobile',
                'postal_code',
            ],
            self::SCE_REMOVE_ADDRESS => [
                'id',
            ],
            self::SCE_SET_DEFAULT => [
                'id',
            ],
            self::SCE_EDIT_ADDRESS => [
                'consignee',
                'province',
                'city',
                'district',
                'detail',
                'mobile',
                'postal_code',
                'id',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['city', 'district'],
                'default',
                'value' => 0,
            ],
            [
                ['consignee', 'province', 'city', 'district', 'detail', 'mobile', 'postal_code', 'id'],
                'required',
                'except' => [self::SCE_EDIT_ADDRESS],
                'message' => 9001,
            ],
            [
                ['province', 'city', 'district', 'id'],
                'required',
                'on' => [self::SCE_EDIT_ADDRESS],
                'message' => 9001,
            ],
            [
                ['consignee'],
                'string',
                'length' => [1, 30],
                'message' => 3061,
                'tooLong' => 3061,
                'tooShort' => 3061,
            ],
            [
                ['district'],
                'common\validators\district\DistrictValidator',
                'province' => $this->province,
                'city' => $this->city,
                'message' => 3063,
            ],
            [
                ['detail'],
                'string',
                'length' => [1, 200],
                'message' => 3062,
                'tooShort' => 3062,
                'tooLong' => 3062,
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
            [
                ['id'],
                'common\validators\address\IdValidator',
                'userId' => Yii::$app->user->id,
                'validateIsDefault' => $this->scenario == self::SCE_SET_DEFAULT ? true : false,
                'message' => 3071,
            ],
            [
                ['id'],
                'exist',
                'targetClass'=>CustomUserAddressAR::className(),
                'filter'=>['custom_user_id'=>Yii::$app->user->id],
                'message'=>9001,
            ],
        ];
    }

    public function editAddress(){
        $address = new Address(['id' => $this->id]);
        $attributes = array_filter([
            'consignee' => $this->consignee,
            'province' => $this->province,
            'city' => $this->city,
            'district' => $this->district,
            'detail' => $this->detail,
            'mobile' => $this->mobile,
            'postalCode' => $this->postal_code,
        ], function($v){
            return (!is_null($v) && $v !== false && $v !== '');
        });
        if($address->set($attributes) === false){
            $this->addError('editAddress', 3091);
            return false;
        }
        return true;
    }

    public function setDefault(){
        if(!Yii::$app->CustomUser->address->setDefaultAddress(new Address(['id' => $this->id]))){
            $this->addError('setDefault', 3081);
            return false;
        }
        return true;
    }

    public function removeAddress(){
        $transaction = Yii::$app->db->beginTransaction();
        $address = CustomUserAddressAR::findOne($this->id);
        if($address->delete()){
            if ($address->default){
                if($_address = CustomUserAddressAR::findOne(['custom_user_id' => Yii::$app->user->id])){
                    $_address = new Address(['id' => $_address->id]);
                    if(!Yii::$app->CustomUser->address->setDefaultAddress($_address)){
                        $transaction->rollBack();
                        $this->addError('removeAddress', 3072);
                        return false;
                    }
                }
            }
            $transaction->commit();
            return true;
        }
        $transaction->rollBack();
        $this->addError('removeAddress', 3072);
        return false;
    }

    public function addAddress(){
        $transaction = Yii::$app->db->beginTransaction();
        $address = AddressHandler::create([
            'custom_user_id' => Yii::$app->user->id,
            'consignee' => $this->consignee,
            'district_province_id' => $this->province,
            'district_city_id' => $this->city,
            'district_district_id' => $this->district,
            'detailed_address' => $this->detail,
            'mobile' => $this->mobile,
            'postal_code' => $this->postal_code,
        ]);
        if($address){
            if(Yii::$app->CustomUser->address->count == 1){
                if(Yii::$app->CustomUser->address->setDefaultAddress($address)){
                    $transaction->commit();
                    return true;
                }else{
                    $transaction->rollBack();
                    return false;
                }
            }else{
                $transaction->commit();
                return true;
            }
        }else{
            $transaction->rollBack();
            return false;
        }
    }

    public static function displayList(){
        try{
            $addresses = Yii::$app->CustomUser->address->list;
            $list = [];
            foreach($addresses as $address){
                $list[] = [
                    'id' => $address->id,
                    'consignee' => $address->consignee,
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
                    'detail' => $address->detail,
                    'mobile' => $address->mobile,
                    'postal_code' => $address->postalCode,
                    'is_default' => $address->isDefault,
                ];
            }
            return $list;

        }catch (\Exception $exception){
            return [];
        }

    }

}
