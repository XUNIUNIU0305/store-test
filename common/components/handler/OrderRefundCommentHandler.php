<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 10:04
 */

namespace common\components\handler;


use common\ActiveRecord\OrderRefundCommentAR;
use common\models\parts\order\OrderRefund;
use Yii;

class OrderRefundCommentHandler extends  Handler
{

    //创建日志
    public static function create(OrderRefund $refund,string $comments,$admin_user_id=0,$return="throw"){
        return Yii::$app->RQ->AR(new OrderRefundCommentAR())->insert([
            'order_refund_id'=>$refund->id,
            'comments'=>$comments,
            'admin_user_id'=>$admin_user_id,
            'post_time'=>time(),
        ],$return);
    }


    //获取评论列表
    public static function getList(OrderRefund $refund){

        return Yii::$app->RQ->AR(new OrderRefundCommentAR())->all(
            [
                'select'=>['id','comments','admin_user_id','post_time'],
                'where'=>['order_refund_id'=>$refund->id]
            ]
        );
    }
}