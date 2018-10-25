<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 10:01
 */

namespace common\ActiveRecord;



use yii\db\ActiveRecord;

class OrderRefundCommentAR extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%order_refund_comment}}';
    }

}