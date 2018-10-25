<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-4-26
 * Time: 下午4:28
 */

namespace console\models\AmqpTask;

use common\components\amqp\AmqpTaskAbstract;
use common\models\parts\Order;
use common\models\parts\order\OrderCustomRecord;
use common\models\parts\order\OrderProductRecord;
use common\ActiveRecord\OrderAR;
use Yii;
use yii\db\Exception;

class DeleteOrderRecordTask extends AmqpTaskAbstract
{
    public $order_id; // 订单id

    public function run()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $time = 1;
        do{
            sleep($time);
            if(OrderAR::findOne(['id' => $this->order_id, 'status' => Order::STATUS_CANCELED])){
                break;
            }else{
                ++$time;
            }
        }while($time < 10);
        try {
            $order = new Order(['id' => $this->order_id]);
            OrderCustomRecord::deleteRecord($order);
            OrderProductRecord::deleteRecord($order);
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}
