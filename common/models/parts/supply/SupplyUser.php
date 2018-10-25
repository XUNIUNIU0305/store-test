<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/29
 * Time: 16:40
 */

namespace common\models\parts\supply;


use common\ActiveRecord\CouponRuleAR;
use common\ActiveRecord\CouponRuleSupplyAR;
use common\ActiveRecord\SupplyUserAR;
use common\models\parts\coupon\CouponRule;
use common\models\parts\custom\CustomUser;
use common\models\parts\district\City;
use common\models\parts\district\District;
use common\models\parts\district\Province;
use yii\base\Exception;
use yii\base\InvalidCallException;
use yii\base\Object;
use Yii;

class SupplyUser extends Object
{


    public $id;

    protected $AR;


    public function init()
    {
        if (!$this->id || !$this->AR = SupplyUserAR::findOne($this->id))
        {
            throw new InvalidCallException();
        }
    }

    //获取账号
    public function getAccount()
    {
        return $this->AR->account;
    }

    //获取店名
    public function getStoreName()
    {
        return $this->AR->store_name;
    }

    //获取品牌名称
    public function getBrandName()
    {
        return $this->AR->brand_name;
    }

    //获取公司名称
    public function getCompanyName()
    {
        return $this->AR->company_name;
    }

    //获取图片
    public function getHeaderImg()
    {
        return $this->AR->header_img;
    }

    //获取联系人姓名
    public function getRealName()
    {
        return $this->AR->real_name;
    }

    //获取手机号码
    public function getMobile()
    {
        return $this->AR->mobile;
    }

    //获取区号
    public function getAreaCode()
    {
        return $this->AR->area_code;
    }

    //获取固定电话
    public function getTelephone()
    {
        return $this->AR->telephone;
    }

    //获取地址
    public function getAddress()
    {
        return $this->AR->address;
    }

    //获取省份
    public function getProvince($isName = false)
    {
        if (!$isName)
        {
            return $this->AR->province;
        }
        if ($this->AR->province > 0)
        {
            return new Province(['provinceId' => $this->AR->province]);
        }
        return false;

    }

    //获取城市
    public function getCity($isName = false)
    {
        if (!$isName)
        {
            return $this->AR->city;
        }
        else
        {
            if ($this->AR->city > 0)
            {
                return new City(['cityId' => $this->AR->city]);
            }
            return false;
        }
    }

    //获取区域
    public function getDistrict($isName = false)
    {
        if (!$isName)
        {
            return $this->AR->district;
        }
        if ($this->AR->district > 0)
        {
            return new District(['districtId' => $this->AR->district]);
        }
        return false;
    }


    //保存多值
    public function updateAttr($data, $return = "throw")
    {
        return Yii::$app->RQ->AR($this->AR)->update($data, $return);
    }

}