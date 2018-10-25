<?php
namespace common\models\parts;

use common\ActiveRecord\ProductAR;
use common\models\parts\district\City;
use common\models\parts\district\District;
use common\models\parts\district\Province;
use Yii;
use yii\base\Object;
use common\ActiveRecord\SupplyUserAR;
use yii\base\InvalidConfigException;
use yii\base\InvalidCallException;

class Supplier extends Object{

    //supply_user表 ID
    public $id;

    //ActiveRecord SupplyUserAR
    protected $AR;

    public function init(){
        if(!$this->id ||
            !$this->AR = SupplyUserAR::findOne($this->id)
        )throw new InvalidConfigException;
    }

    /**
     * 获取账号
     *
     * @return string
     */
    public function getAccount(){
        return $this->AR->account;
    }


    /**
     * Author:JiangYi
     * Date:2017/5/27
     * Desc:获取所在省份信息
     * @return bool|District
     */
    public function getProvince(){
        try{
            return new Province(['provinceId'=>$this->AR->province]);
        }catch (\Exception $e){
            return false;
        }
    }

    /**
     * Author:JiangYi
     * Date:2017/5/27
     * Desc:获取所在省份信息
     * @return bool|District
     */
    public function getCity(){
        try{
            return new City(['cityId'=>$this->AR->city,'provinceId'=>$this->AR->province]);
        }catch (\Exception $e){
            return false;
        }
    }

    /**
     * Author:JiangYi
     * Date:2017/5/27
     * Desc:获取所在省份信息
     * @return bool|District
     */
    public function getDistrict(){
        try{
            return new District(['cityId'=>$this->AR->city,'districtId'=>$this->AR->district,'provinceId'=>$this->AR->province]);
        }catch (\Exception $e){
            return false;
        }
    }

    /**
     * Author:JiangYi
     * Date:2017/05/27
     * Desc:获取商户下所有商口数量
     * @return mixed
     */
    public function getGoodsQuantity(){
        return Yii::$app->RQ->AR(new ProductAR())->count(
            [
                'where'=>[
                    'supply_user_id'=>$this->id,
                    'sale_status'=>Product::SALE_STATUS_ONSALE,
                ],
            ]
        );
    }

    /**
     * Author:JiangYi
     * Date:2017/5/27
     * Desc:获取图像名称
     * @return mixed
     */
    public function getHeaderImg(){
        return $this->AR->header_img;
    }

    /**
     * Author:JiangYi
     * Date:2017/5/27
     * Desc:获取图像名称
     * @return mixed
     */
    public function getAddress(){
        return $this->AR->address;
    }
    /**
     * Author:JiangYi
     * Date:2017/5/27
     * Desc:获取品牌名称
     * @return mixed
     */
    public function getBrandName(){
        return $this->AR->brand_name;
    }
    /**
     * 获取店铺名称
     *
     * @return string
     */
    public function getStoreName(){
        return $this->AR->store_name;
    }

    /**
     * 获取公司名称
     *
     * @return string
     */
    public function getCompanyName(){
        return $this->AR->company_name;
    }

    /**
     * 获取银行账号
     *
     * @return integer
     */
    public function getBankAccount(){
        return $this->AR->bank_account;
    }

    //禁止设置账号
    protected final function setAccount(){
        throw new InvalidCallException('forbid to set account');
    }

    //禁止获取密码
    protected final function getPasswd(){
        throw new InvalidCallException('forbid to get password');
    }

    //禁止设置密码
    protected final function setpasswd(){
        throw new InvalidCallException('forbid to set password');
    }
}
