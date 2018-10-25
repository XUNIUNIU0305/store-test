<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/31
 * Time: 17:08
 */

namespace common\components\handler;


use common\ActiveRecord\OrderRefundAR;
use common\ActiveRecord\OrderRefundImgAR;
use common\models\parts\ItemInOrder;
use common\models\parts\order\OrderRefund;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;

class OrderRefundImgHandler extends Handler
{


    //删除图片记录
    public static function delete(OrderRefund $orderRefund,int  $id,$return="throw"){
        return Yii::$app->RQ->AR(new OrderRefundImgAR())->delete(['order_refund_id'=>$orderRefund->id,'id'=>$id],$return);
    }

}