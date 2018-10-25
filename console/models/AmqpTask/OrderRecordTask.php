<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-4-25
 * Time: 下午2:13
 */

namespace console\models\AmqpTask;

use common\components\amqp\AmqpTaskAbstract;
use common\models\parts\Order;
use common\models\parts\order\OrderCustomRecord;
use common\models\parts\order\OrderProductRecord;
use Yii;
use yii\db\Exception;

class OrderRecordTask extends AmqpTaskAbstract
{
    public $order_id; // 订单id

    public function run()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $time = 1;
        do{
            try{
                sleep($time);
                $order = new Order(['id' => $this->order_id]);
                $time = 11;
            }catch(\Exception $e){
                ++$time;
                $order = false;
            }
        }while($time < 10);
        try {
            if(!$order)throw new \Exception;
            if (OrderCustomRecord::addRecord($order) && OrderProductRecord::addRecord($order)) {
                $transaction->commit();
                return true;
            } else {
                throw new \Exception;
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}
