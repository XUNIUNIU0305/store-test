<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/17
 * Time: 16:16
 */

namespace wechat\models;

use common\components\handler\Handler;
use Yii;

class AddressModel extends \custom\modules\account\models\AddressModel
{
    const SCE_ADDRESS_LIST = 'address_list';
    const SCE_ADDRESS_ONE = 'address_one';

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCE_ADDRESS_LIST] = [];
        $scenarios[self::SCE_ADDRESS_ONE] = [
             'id'
        ];
        return $scenarios;
    }

    public function addressOne()
    {
        $address = Yii::$app->CustomUser->address->getList($this->id);
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

    /**
     * 获取地址列表
     * @return array|bool
     */
    public function addressList()
    {
        try {
            $address = Yii::$app->CustomUser->address->getList();
            if (!$address) return [];
            return array_map(function($add){
                return [
                    'id' => $add->id,
                    'consignee' => $add->consignee,
                    'province' => [
                        'id' => $add->province,
                        'name' => $add->getProvince(true),
                    ],
                    'city' => [
                        'id' => $add->city,
                        'name' => $add->getCity(true),
                    ],
                    'district' => [
                        'id' => $add->district,
                        'name' => $add->getDistrict(true),
                    ],
                    'detail' => $add->detail,
                    'mobile' => $add->mobile,
                    'postal_code' => $add->postalCode,
                    'is_default' => $add->isDefault,
                ];
            }, $address);
        } catch (\Exception $e){
            $this->addError('', 11001);
            return false;
        }
    }
}