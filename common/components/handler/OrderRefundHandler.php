<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/31
 * Time: 17:08
 */

namespace common\components\handler;


use common\ActiveRecord\OrderRefundAR;
use common\models\parts\ItemInOrder;
use common\models\parts\Order;
use common\models\parts\order\OrderRefund;
use custom\models\parts\RefundIdGenerator;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;

class OrderRefundHandler extends Handler
{
    const SEARCH_TYPE_EXEC_FOR_ADMIN = -2;//客服已处理列表
    const SEARCH_TYPE_EXEC_FOR_SUPPLY = -1;//供应商已处理列表

    //查询获取退换申请单列表
    public static function getRefundOrderList(int $currentPage, int $pageSize, int $type = null, int $status = null, string $orderCode = '', int $userId = null, int $supplyId = null, array $orderBy = ['id' => SORT_DESC]){
        $query = OrderRefundAR::find()
            ->select(['id'])
            ->filterWhere(['type' => $type])
            ->andFilterWhere(['custom_user_id' => $userId])
            ->andFilterWhere(['supply_user_id' => $supplyId])
            ->andFilterWhere(['code' => $orderCode]);
        if(!is_null($status)){
            if($status != self::SEARCH_TYPE_EXEC_FOR_ADMIN && $status != self::SEARCH_TYPE_EXEC_FOR_SUPPLY){
                $query->andWhere(['status' => $status]);
            }else{
                if($status == self::SEARCH_TYPE_EXEC_FOR_SUPPLY){
                    $query->andWhere([
                        'status' => [
                            OrderRefund::REFUND_STATUS_SUPPLY_AGREE,
                            OrderRefund::REFUND_STATUS_REFUND_MONEY,
                            OrderRefund::REFUND_STATUS_SENDED,
                            OrderRefund::REFUND_STATUS_FINISHED,
                            OrderRefund::REFUND_STATUS_RECEIVE_CONFIRM,
                        ],
                    ]);
                }elseif($status == self::SEARCH_TYPE_EXEC_FOR_ADMIN){
                    $query->andWhere([
                        'status' => [
                            OrderRefund::REFUND_STATUS_AGREE,
                            OrderRefund::REFUND_STATUS_REJECT,
                            OrderRefund::REFUND_STATUS_SUPPLY_AGREE,
                            OrderRefund::REFUND_STATUS_BACK,
                            OrderRefund::REFUND_STATUS_REFUND_MONEY,
                            OrderRefund::REFUND_STATUS_SENDED,
                            OrderRefund::REFUND_STATUS_FINISHED,
                        ],
                    ]);
                }
            }
        }
        $query->asArray();

        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 1;
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => $orderBy,
            ],
        ]);
    }


    //创建退换申请
    public static function create(ItemInOrder $item, int $quantity, string $reason, array $images = null, $return = "throw")
    {
        $data = [
            'code' => self::createOrderCode(),
            'type' => OrderRefund::REFUND_TYPE_NEW,
            'order_item_id' => $item->id,
            'custom_user_id' => $item->getCustomerId(),
            'supply_user_id' => $item->getSupplierId(),
            'quantity' => $quantity,
            'sub_total' => $quantity * $item->getPrice(),
            'create_time' => time(),
            'reason' => $reason,
            'status' => OrderRefund::REFUND_STATUS_NEW,
        ];

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$id = Yii::$app->RQ->AR(new OrderRefundAR())->insert($data, $return)) {
                //写入订订单数据失败
                $transaction->rollBack();
                return false;
            }
            //保存图片信息
            $orderRefund = new OrderRefund(['id' => $id]);

            if ($images) {
                if (!$orderRefund->createImages($images, OrderRefund::IMAGE_TYPE_CUSTOMER, 0,$return)) {
                    $transaction->rollBack();
                    return false;
                }
            }


            //修改订单退货单状态
            if(!$orderRefund->getGoodsItem()->getOrder()->setRefundStatus(Order::REFUND_STATUS_YES)){
                $transaction->rollBack();
                return false;
            }

            //提交数据
            $transaction->commit();
            return true;

        } catch (Exception $e) {

            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e->getMessage());
        }
    }


    //创建生成订单号
    private static function createOrderCode()
    {
        return (new RefundIdGenerator())->getId();
    }


}
