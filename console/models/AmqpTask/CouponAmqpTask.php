<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/3
 * Time: 10:10
 */

namespace console\models\AmqpTask;

use common\components\amqp\AmqpTaskAbstract;
use common\models\parts\coupon\Coupon;

class CouponAmqpTask extends AmqpTaskAbstract
{
    public $quantity;//创建记录数量
    public $coupon_id;//创建优惠券ID

    public function run()
    {
        $coupon = new Coupon(['id' => $this->coupon_id]);
        if ($coupon->createTicket($this->quantity)) {
            return true;
        }
        return false;
    }

}