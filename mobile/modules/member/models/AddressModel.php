<?php
/**
 * User: JiangYi
 * Date: 2017/5/19
 * Time: 11:56
 * Desc:
 */

namespace mobile\modules\member\models;

use common\ActiveRecord\CustomUserAddressAR;
use custom\components\handler\AddressHandler;
use Yii;

class AddressModel extends  \custom\modules\account\models\AddressModel
{
    const SCE_GET_DEFAULT_ADDRESS="get_default_address";//获取用户默认地址
    const SCE_EDIT_VIEW="get_edit_address";//获取当前需要编辑的地址信息

    public  $address_id;
    public $is_default;

    public function scenarios()
    {
        $scenario=[
            self::SCE_GET_DEFAULT_ADDRESS=>[],
            self::SCE_EDIT_VIEW=>['address_id'],
            self::SCE_EDIT_ADDRESS => [
                'consignee',
                'province',
                'city',
                'district',
                'detail',
                'mobile',
                'postal_code',
                'id',
                'is_default',
            ],

            self::SCE_ADD_ADDRESS => [
                'consignee',
                'province',
                'city',
                'district',
                'detail',
                'mobile',
                'postal_code',
                'is_default',
            ],
        ];
        return array_merge(parent::scenarios(),$scenario);
    }

    public function rules()
    {
        $rule = [[
            ['address_id'],
            'exist',
            'targetClass'=>CustomUserAddressAR::className(),
            'targetAttribute'=>['address_id'=>'id'],
            'filter'=>['custom_user_id'=>Yii::$app->user->id],
            'message'=>9001,
            'on'=>[self::SCE_EDIT_VIEW],
        ]];
        return array_merge(parent::rules(),$rule);
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
            if(Yii::$app->CustomUser->address->count == 1 || $this->is_default){
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



    public function editAddress()
    {
        if($this->is_default){
            parent::setDefault();
        }
        return parent::editAddress();
    }

    public function getEditAddress(){
        $address = Yii::$app->CustomUser->address->getList($this->address_id);
        return [
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


    public  function getDefaultAddress(){
        $address = Yii::$app->CustomUser->address->getDefaultAddress();
        if ($address){
            return [
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
        return [];
    }

}