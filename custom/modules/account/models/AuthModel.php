<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/7/26
 * Time: 上午11:58
 */

namespace custom\modules\account\models;


use common\ActiveRecord\DistrictCityAR;
use common\ActiveRecord\DistrictDistrictAR;
use common\ActiveRecord\DistrictProvinceAR;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\district\District;
use common\models\parts\OSSImage;
use Yii;

class AuthModel extends Model
{
    const SCE_SUBMIT = 'submit';
    const SCE_AUTH_INFO = 'auth_info';


    public $store_name;
    public $corp_name;
    public $email;
    public $address;
    public $business_licence;
    public $manager_name;
    public $contact_name;
    public $contact_mobile;

    public $district_id;
    public $province_id;
    public $city_id;

    public $card_front;
    public $card_back;

    public $store_front;
    public $store_inside;


    public function scenarios()
    {
        return [
            self::SCE_SUBMIT => [
                'store_name',
                'store_front',
                'store_inside',
                'corp_name',
                'card_front',
                'card_back',
                'email',
                'contact_mobile',
                'contact_name',
                'address',
                'business_licence',
                'province_id',
                'district_id',
                'city_id',
                'manager_name',
            ],
            self::SCE_AUTH_INFO => [],
        ];
    }

    public function rules()
    {
        return [
            [
                ['district_id', 'city_id'],
                'default',
                'value'=>0
            ],
            [
                ['card_front', 'card_back', 'store_front', 'store_inside', 'corp_name', 'contact_mobile', 'contact_name', 'business_licence'],
                'default',
                'value' => true,
            ],
            [
                [
                    'store_name',
                    'corp_name',
                    'email',
                    'address',
                    'business_licence',
                    'province_id',
                    'district_id',
                    'city_id',
                ],
                'required',
                'message' => 9001,
            ],
            [
                ['province_id'],
                'exist',
                'targetClass' => DistrictProvinceAR::className(),
                'targetAttribute' => ['province_id'=>'id'],
                'message' => 3312,
            ], 
            [
                ['email'],
                'email',
                'message' => 3311
            ],

        ];
    }

    /**
     *====================================================
     * 递交审核信息
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function submit()
    {
        try
        {
            $district = new District([
                'districtId' => $this->district_id,
                'provinceId' => $this->province_id,
                'cityId' => $this->city_id,
            ]);

            $authorizeDataGenerator = Yii::$app->CustomUser->auth->newAuthorizeData();
            $authorizeDataGenerator->addStore($this->store_name,
                $this->store_front === true ? null : self::createImage($this->store_front),
                $this->store_inside === true ? null : self::createImage($this->store_inside)
            )
                ->addCorpName($this->corp_name === true ? null : $this->corp_name)
                ->addEmail($this->email)
                ->addAddress($district, $this->address)
                ->addManager($this->manager_name,
                    $this->card_front === true ? null : self::createImage($this->card_front),
                    $this->card_back === true ? null : self::createImage($this->card_back)
                )
                ->addContact($this->contact_name === true ? null : $this->contact_name, $this->contact_mobile === true ? null : $this->contact_mobile)
                ->addBusinessLicence($this->business_licence === true ? null : self::createImage($this->business_licence));
            if ($authorizeDataGenerator->build() == true){
                return true;
            }
        }
        catch (\Exception $exception)
        {
            $this->addError('submit', 3310);
            return false;
        }
    }

    /**
     *====================================================
     * 获取审核信息
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function authInfo()
    {
        $authObj = Yii::$app->CustomUser->auth;
        if (($authorizeDataObj = $authObj->authorizeData)){
            $authorizeDataArr =  Handler::getMultiAttributes($authorizeDataObj, [
                'store_name' => 'storeName',
                'corp_name' => 'corpName',
                'email',
                'address',
                'manager_name' => 'managerName',
                'contact_name' => 'contactName',
                'contact_mobile' => 'contactMobile',
                'district' => 'district',
                'card_front' => 'managerIdcardFront',
                'card_back' => 'managerIdcardBack',
                'store_front' => 'storeFront',
                'store_inside' => 'storeInside',
                'business_licence' => 'businessLicence',
                'comment' => 'AuthorizeComment',
                '_func' => [
                    'contactMobile' => function($mobile){
                        if($mobile){
                            return $mobile;
                        }else{
                            return '';
                        }
                    },
                    'district' => function ($district)
                    {
                        return [
                            'province' => [
                                'id' => $district->province->provinceId ? : '',
                                'name' => $district->province->name ? : '',
                            ],
                            'city' => [
                                'id' => $district->city->cityId ? : '',
                                'name' => $district->city->name ? : '',
                            ],
                            'district' => [
                                'id' => $district->districtId ? : '',
                                'name' => $district->name ? : '',
                            ],

                        ];
                    },
                    'managerIdcardFront'=>function($card){
                        if($card){
                            return [
                                'name'=>$card->name,
                                'path'=>$card->getPath()
                            ];
                        }else{
                            return [
                                'name' => '',
                                'path' => '',
                            ];
                        }
                    },
                    'managerIdcardBack'=>function($card){
                        if($card){
                            return [
                                'name'=>$card->name,
                                'path'=>$card->getPath()
                            ];
                        }else{
                            return [
                                'name' => '',
                                'path' => '',
                            ];
                        }
                    },
                    'storeFront'=>function($store){
                        if($store){
                            return [
                                'name'=>$store->name,
                                'path'=>$store->getPath()
                            ];
                        }else{
                            return [
                                'name' => '',
                                'path' => '',
                            ];
                        }
                    },
                    'storeInside'=>function($store){
                        if($store){
                            return [
                                'name'=>$store->name,
                                'path'=>$store->getPath()
                            ];
                        }else{
                            return [
                                'name' => '',
                                'path' => '',
                            ];
                        }
                    },
                    'businessLicence'=>function($licence){
                        if($licence){
                            return [
                                'name'=>$licence->name,
                                'path'=>$licence->getPath()
                            ];
                        }else{
                            return [
                                'name' => '',
                                'path' => '',
                            ];
                        }
                    },
                ],
            ]);
            return array_merge(['status'=>$authObj->status],$authorizeDataArr);
        }
        return [];
    }

    /**
     *====================================================
     * 创建图片对象
     * @param $fileName
     * @return OSSImage
     * @author shuang.li
     *====================================================
     */
    private function createImage($fileName)
    {
        return new OSSImage([
            'images' => ['filename' => $fileName],
        ]);
    }
}
