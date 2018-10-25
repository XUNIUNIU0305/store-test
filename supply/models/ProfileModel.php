<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/29
 * Time: 15:23
 */

namespace supply\models;


use common\ActiveRecord\DistrictCityAR;
use common\models\Model;
use common\models\parts\OSSImage;
use Yii;

class ProfileModel extends Model
{

    CONST SCE_GET_PROFILE = "get_profile";//获取商户资料

    CONST SCE_SAVE_PROFILE = "save_profile";//保存商户资料


    public $brand_name;//品牌名称
    public $company_name;//公司名称
    public $mobile;//手机号码
    public $area_code;//区域
    public $telephone;//固定电话
    public $province;//省份
    public $city;//城市
    public $district;//区域
    public $address;//地址
    public $header_img;//用户图像
    public $real_name;//联系人姓名

    //设置场景
    public function scenarios()
    {
        return [
            self::SCE_GET_PROFILE => [],
            self::SCE_SAVE_PROFILE => ['brand_name', 'company_name', 'mobile', 'area_code', 'telephone','real_name', 'province', 'city', 'district', 'address', 'header_img'],
        ];
    }

    //验证规则
    public function rules()
    {
        return [
            [
                ['city'],
                'default',
                'value'=>0,
            ],
            [
                ['brand_name', 'company_name', 'mobile','real_name', 'area_code', 'telephone', 'city', 'district', 'address'],
                'required',
                'message' => 9001,
            ],
            [
                ['mobile'],
                'integer',
                'min' => 10000000000,
                'max' => 19999999999,
                'tooSmall' => 3166,
                'tooBig' => 3166,
                'message' => 3166,
            ],
            [
                ['area_code'],
                'exist',
                'targetClass' => DistrictCityAR::className(),
                'targetAttribute' => ['area_code' => 'citycode'],
                'message' => 1121,
            ],
            [
                ['district'],
                'common\validators\district\DistrictValidator',
                'province' => $this->province,
                'city' => $this->city,
                'message' => 3165,
            ],
        ];

    }

    //保存供应商信息
    public function saveProfile()
    {
        $header_img = Yii::$app->SupplyUser->User->getHeaderImg();
        if (!empty($this->header_img)) {
            $images = new OSSImage([
                'images' => ['filename' => $this->header_img],
            ]);
            $header_img = current($images->getPath());
        }

        $data = [
            'header_img' => $header_img,
            'brand_name' => $this->brand_name,
            'company_name' => $this->company_name,
            'mobile' => $this->mobile,
            'area_code' => $this->area_code,
            'telephone' => $this->telephone,
            'province' => $this->province,
            'real_name'=>$this->real_name,
            'city' => $this->city,
            'district' => $this->district,
            'address' => $this->address
        ];
        if (Yii::$app->SupplyUser->User->updateAttr($data)!==false) {
            return true;
        }
        $this->addError('saveProfile', 1120);
        return false;

    }


    //获取商户信息
    public function getProfile()
    {

        return [
            'store_name' => Yii::$app->SupplyUser->User->getStoreName(),
            'brand_name' => Yii::$app->SupplyUser->User->getBrandName(),
            'company_name' => Yii::$app->SupplyUser->User->getCompanyName(),
            'header_img' => Yii::$app->SupplyUser->User->getHeaderImg(),
            'mobile' => Yii::$app->SupplyUser->User->getMobile(),
            'area_code' => Yii::$app->SupplyUser->User->getAreaCode(),
            'real_name'=>Yii::$app->SupplyUser->User->getRealName(),
            'telephone' => Yii::$app->SupplyUser->User->getTelephone(),
            'address' => Yii::$app->SupplyUser->User->getAddress(),
            'province_id' => Yii::$app->SupplyUser->User->getProvince(),
            'city_id' => Yii::$app->SupplyUser->User->getCity(),
            'district_id' => Yii::$app->SupplyUser->User->getDistrict(),
        ];
    }


}