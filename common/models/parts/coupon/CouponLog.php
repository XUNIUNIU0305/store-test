<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/27
 * Time: 10:30
 */

namespace common\models\parts\coupon;




use common\ActiveRecord\CouponAR;
use common\ActiveRecord\CouponLogAR;
use common\components\handler\coupon\CouponLogHandler;
use common\models\Object;
use common\models\parts\supply\SupplyUser;
use yii\base\InvalidCallException;

class CouponLog extends Object
{


    public $id;
    public function init(){
        if(!$this->id||!$this->AR=CouponLogAR::findOne($this->id))throw new InvalidCallException();
    }



}