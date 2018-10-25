<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/31
 * Time: 17:05
 */

namespace common\ActiveRecord;



use yii\db\ActiveRecord;

class OrderRefundImgAR extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%order_refund_img}}';
    }

}