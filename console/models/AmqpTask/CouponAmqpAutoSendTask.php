<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/3
 * Time: 10:10
 */

namespace console\models\AmqpTask;


use common\components\amqp\AmqpTaskAbstract;
use common\components\amqp\Message;
use common\components\handler\coupon\CouponRuleHandler;
use custom\models\parts\trade\Trade;
use Yii;
use yii\base\Exception;

class CouponAmqpAutoSendTask extends AmqpTaskAbstract
{
    public $trade_id;//创建记录数量

    public function run()
    {

        //执行发送操作
        try {
            $trade = new Trade(['id' => $this->trade_id]);
        } catch (Exception $e) {
            return false;
        }

        if (CouponRuleHandler::sendTicket($trade)) {
            return true;
        }
        return false;

    }


}