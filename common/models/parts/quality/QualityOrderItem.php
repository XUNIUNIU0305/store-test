<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 18:04
 */

namespace common\models\parts\quality;


use common\ActiveRecord\QualityOrderAR;

use common\ActiveRecord\QualityOrderItemAR;
use common\components\handler\quality\QualityOrderItemHandler;
use common\models\parts\car\CarBrand;
use common\models\parts\car\CarType;
use common\models\parts\custom\CustomUserTechnician;
use yii\base\InvalidCallException;
use yii\base\Object;

class QualityOrderItem extends  Object
{
    const CAR_FRAME_LENGTH = 17;

    public $id;
    public $AR;

    public function init(){
        if(!$this->id||!$this->AR=QualityOrderItemAR::findOne($this->id))throw new InvalidCallException();
    }

    //获取产品序列号
    public function getCode(){
        return $this->AR->code;
    }

    //获取所属订单信息s
    public function getOrder(){
        return new QualityOrder(['id'=>$this->AR->quality_order_id]);
    }
    //获取套餐
    public function getPackage(){
        return new QualityPackage(['id'=>$this->AR->quality_package_id]);
    }
    //获取施工位置信息
    public function getPlace(){
        return new QualityPlace(['id'=>$this->AR->quality_place_id]);
    }

    //获取技师信息
    public function getTechnician(){
        return new BusinessAreaTechnican(['id'=>$this->AR->custom_user_technician_id]);
    }

    //获取销售
    public function getSales(){
        return $this->AR->sales;
    }

    // 悠耐独有
    public function getOption()
    {
        return $this->AR->work_option;
    }
}