<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-18
 * Time: 上午11:29
 */

namespace console\controllers\business;

use common\ActiveRecord\BusinessAreaAR;
use common\ActiveRecord\MembraneOrderAR;
use common\models\parts\MembraneOrder;
use yii\console\Exception;

trait MembraneTrait
{
    /**
     * 填充不完整订单
     * @return int
     */
    public function actionFill()
    {
        try{
            $status = [
                MembraneOrder::STATUS_ACCEPTED,
                MembraneOrder::STATUS_FINISHED
            ];
            /** @var MembraneOrderAR $items */
            $items = MembraneOrderAR::find()->all();
            foreach ($items as $item){
                if(!$item->business_third_area_id) continue;
                if(!$item->business_third_user_id){
                    $area = $area = $this->findArea($item->business_third_area_id);
                    $item->business_third_user_id = $area->business_user_asleader_id;
                }

                if(in_array(intval($item->status), $status) && !$item->business_third_area_leader_id){
                    $area = $area = $this->findArea($item->business_third_area_id);
                    $item->business_third_area_leader_id = $area->business_user_asleader_id;
                }
                $item->update(false);
            }
            return 0;
        } catch (\Exception $e){
            echo $e->getMessage() . "\n";
            return 1;
        }
    }

    private $areas = [];

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    private function findArea($id)
    {
        if(!isset($this->areas[$id])){
            if($area = BusinessAreaAR::findOne($id))
                $this->areas[$id] = $area;
            else
                throw new Exception('区域找不到:' . $id);
        }
        return $this->areas[$id];
    }

    /**
     * 统一支付到供应商
     * @param $id
     * @return int
     */
    public function actionPay($id)
    {
        $items = MembraneOrderAR::find()
            ->where(['status' => MembraneOrder::STATUS_FINISHED])
            ->andWhere(['<=', 'id', $id])->all();

        $transaction = \Yii::$app->db->beginTransaction();
        try{
            foreach ($items as $item){
                $order = new MembraneOrder(['AR' => $item]);
                if($order->isPayed()){
                    echo "订单：$order->no 已经支付过了.\n";
                } else {
                    $order->payToWallet();
                }
            }
            $transaction->commit();
            return 0;
        } catch (\Exception $e){
            $transaction->rollBack();
            echo $e->getMessage() . "\n";
            return 1;
        }
    }
}