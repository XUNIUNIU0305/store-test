<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 18:04
 */

namespace common\models\parts\quality;


use common\ActiveRecord\CarBrandAR;
use common\ActiveRecord\CarTypeAR;
use common\ActiveRecord\QualityOrderAR;

use common\components\handler\quality\QualityOrderItemHandler;
use common\models\parts\car\CarBrand;
use common\models\parts\car\CarType;
use common\models\parts\custom\CustomUser;
use common\models\parts\custom\CustomUserTechnician;
use yii\base\InvalidCallException;
use yii\base\Object;

class QualityOrder extends  Object
{

    public $id;
    public $AR;

    public function init(){
        if(!$this->id||!$this->AR=QualityOrderAR::findOne($this->id))throw new InvalidCallException();
    }

    //获取单号
    public function getCode(){
        return $this->AR->code;
    }
    //获取姓名
    public function getOwnerName(){
        return $this->AR->owner_name;
    }
    //获取手机
    public function getOwnerMobile(){
        return $this->AR->owner_mobile;
    }

    //获取地址
    public function getOwnerAddress(){
        return $this->AR->owner_address;
    }
    //获取固定电话
    public function getOwnerTelephone(){
        return $this->AR->owner_telephone;
    }
    // 获取邮件
    public function getEmail(){
        return $this->AR->owner_email;
    }
    //获取车牌号
    public function getCarNumber(){
        return $this->AR->car_number;
    }
    //获取车架号
    public function getCarFrame(){
        return $this->AR->car_frame;
    }
    //获取价格区间
    public function getCarPriceRange(){
        return $this->AR->car_price_range;
    }
    //获取品牌
    public function getBrand(){
        return CarBrandAR::find()->select(['name'])->where(['id' => $this->AR->car_brand_id])->scalar() ?? '';
    }
    //获取车型名称
    public function getCarType(){
        return CarTypeAR::find()->select(['name'])->where(['id' => $this->AR->car_type_id])->scalar() ?? '';
    }
    //获取施工时间
    public function getConstructTime(){
        return $this->AR->construct_time>0?date("Y-m-d",$this->AR->construct_time):'';
    }
    //获取完工时间
    public function getFinishedTime(){
        return $this->AR->finished_time>0?date("Y-m-d",$this->AR->finished_time):"";
    }
    //获取价格
    public function getPrice(){
        return $this->AR->price;
    }
    //获取客户信息
    public function getCustomer(){
        return new CustomUser(['id'=>$this->AR->custom_user_id]);
    }
    //获取价格
    public function getConstructUnit(){
        return $this->AR->construct_unit;
    }

    //获取质保单项目
    public function getItems(){
        return array_map(function($item){
            $item=new QualityOrderItem(['id'=>$item['id']]);
            return [
                'id'=>$item->id,
                'code'=>$item->getCode(),
                'package_name'=>$item->getPackage()->getName(),
                'place_name'=>$item->getPlace()->getName(),
                'sales'=>$item->getSales(),
                'technician'=>$item->getTechnician()->getName(),
                'work_option'=>$item->getOption(),
            ];
        },QualityOrderItemHandler::getList($this));
    }

    //获取膜品牌
    public function getMembraneBrand()
    {
        return QualityOrderItemHandler::getMembraneBrand($this);
    }
    
}