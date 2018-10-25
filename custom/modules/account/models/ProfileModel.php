<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/23
 * Time: 10:32
 */

namespace custom\modules\account\models;


use common\models\Model;
use common\models\parts\OSSImage;
use custom\models\parts\OSSUploadConfigForCustom;
use Yii;

class ProfileModel extends Model
{

    const SCE_UPLOAD_HEADERIMG = "upload_header_img";//上传用户图像
    const SCE_SAVE_PROFILE = "save_profile";//保存用户资料
    const SCE_GET_PROFILE = 'get_profile';//获取用户资料

    public $file_name;
    public $shop_name;
    public $nick_name;
    public $province;
    public $city;
    public $district;
    public $email;
    public $file_suffix;
    public $verify_code;
    public $invoice_title;
    public $invoice_number;


    public function scenarios()
    {

        return [
            self::SCE_SAVE_PROFILE => [
                'file_name',
                'shop_name',
                'nick_name',
                'province',
                'city',
                'district',
                'email',
                'verify_code',
                'invoice_title',
                'invoice_number',
            ],
            self::SCE_UPLOAD_HEADERIMG => ['file_suffix'],
            self::SCE_GET_PROFILE => [],
        ];
    }

    public function rules()
    {

        return [
            [
                ['shop_name', 'nick_name', 'province', 'city', 'district', 'email', 'verify_code', 'invoice_title', 'invoice_number'],
                'required',
                'message' => 9001,
            ],
            [
                ['district'],
                'common\validators\district\DistrictValidator',
                'province' => $this->province,
                'city' => $this->city,
                'message' => 3165,
            ],
            [
                ['email'],
                'email',
                'message' => 3168,
            ],
            [
                ['verify_code'],
                'common\validators\SmsValidator',
                'mobile' => Yii::$app->CustomUser->CurrentUser->getMobile(),
                'message' => 3252,
            ]

        ];
    }

    /**
     * @return array
     */
    public function getProfile()
    {
        $user = Yii::$app->CustomUser->CurrentUser;
        return [
            'shopName' => $user->getShopName(),
            'nickName' => $user->getNickName(),
            'headerImg' => $user->getHeaderImg(),
            'provinceId' => $user->getProvince()->provinceId,
            'cityId' => $user->getCity()->cityId,
            'districtId' => $user->getDistrict()->districtId,
            'mobile' => $user->getMobile(),
            'email' => $user->getEmail(),
            'invoice_title' => $user->getInvoiceTitle(),
            'invoice_number' => $user->getInvoiceNumber(),
        ];
    }


    //上传图片
    public function uploadHeaderImg()
    {
        $uploadConfig = new OSSUploadConfigForCustom([
            'userId' => Yii::$app->user->id,
            'fileSuffix' => $this->file_suffix,
        ]);
        if ($permission = $uploadConfig->getPermission()) {
            return $permission;
        }
        $this->addError('getOssPermission', 5093);
        return false;
    }


    //保存用户基本信息
    public function saveProfile()
    {

        $headerImg = Yii::$app->CustomUser->CurrentUser->getHeaderImg();


        if (!empty($this->file_name)) {
            $images = new OSSImage([
                'images' => ['filename' => $this->file_name],
            ]);
            $headerImg = current($images->getPath());
        }
        if (Yii::$app->CustomUser->CurrentUser->setUserAttr([
                'header_img' => $headerImg,
                'shop_name' => $this->shop_name,
                'nick_name' => $this->nick_name,
                'district_province_id' => $this->province,
                'district_city_id' => $this->city,
                'district_district_id' => $this->district,
                'email' => $this->email,
                'invoice_title' => $this->invoice_title,
                'invoice_number' => $this->invoice_number,
            ], false) !== false
        ) {
            return true;
        }
        $this->addError('saveProfile', 3256);
        return false;
    }


}
