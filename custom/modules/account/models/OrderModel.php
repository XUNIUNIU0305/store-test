<?php
namespace custom\modules\account\models;

use common\components\handler\OSSImageHandler;
use common\models\parts\OrderCustomization;
use Yii;
use common\models\Model;
use common\models\parts\Order;
use common\components\handler\Handler;
use custom\components\handler\TradeHandler;
use custom\models\parts\trade\PaymentMethod;
use custom\components\handler\CustomRechargeApplyHandler;
use custom\components\handler\OrderHandler;
use yii\helpers\Url;
use custom\models\parts\UrlParamCrypt;

class OrderModel extends Model{

    const SCE_GET_LIST = 'get_list';
    const SCE_GET_ACCOUNT_ORDERS = 'get_account_orders';
    const SCE_GET_ORDER_INFO = 'get_order_info';
    const SCE_CONFIRM_ORDER = 'confirm_order';
    const SCE_PAY_ORDERS = 'pay_orders';
    const SCE_CANCEL_ORDERS = 'cancel_orders';

    public $status;
    public $current_page;
    public $page_size;
    public $user_account;
    public $no;
    public $orders_no;
    public $payment;

    public function scenarios(){
        return [
            self::SCE_GET_LIST => [
                'status',
                'current_page',
                'page_size',
            ],
            self::SCE_GET_ACCOUNT_ORDERS => [
                'user_account',
            ],
            self::SCE_GET_ORDER_INFO => [
                'no',
            ],
            self::SCE_CONFIRM_ORDER => [
                'no',
            ],
            self::SCE_PAY_ORDERS => [
                'orders_no',
                'payment',
            ],
            self::SCE_CANCEL_ORDERS => [
                'orders_no',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['status'],
                'default',
                'value' => 0,
            ],
            [
                ['current_page'],
                'default',
                'value' => 1,
            ],
            [
                ['page_size'],
                'default',
                'value' => 10,
            ],
            [
                ['status', 'current_page', 'page_size', 'user_account', 'no', 'orders_no', 'payment'],
                'required',
                'message' => 9001,
            ],
            [
                ['status'],
                'in',
                'range' => Order::getStatuses(),
                'message' => 3111,
            ],
            [
                ['current_page', 'page_size'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['no'],
                'integer',
                'min' => 1000000000,
                'max' => 9999999999,
                'message' => 9002,
                'tooSmall' => 9002,
                'tooBig' => 9002,
            ],
            [
                ['no'],
                'common\validators\order\NoValidator',
                'customerId' => Yii::$app->user->id,
                'message' => 3121,
            ],
            [
                ['orders_no'],
                'each',
                'rule' => [
                    'common\validators\order\NoValidator',
                    'customerId' => Yii::$app->user->id,
                ],
                'allowMessageFromRule' => false,
                'message' => 3151,
            ],
            [
                ['payment'],
                'common\validators\order\PaymentValidator',
                'paymentMethod' => new \custom\models\parts\trade\PaymentMethod,
                'message' => 3152,
            ],
        ];
    }

    public function cancelOrders(){

        $transaction = Yii::$app->db->beginTransaction();
        try{
            $orders = [];
            foreach($this->orders_no as $orderNo){
                $order = new Order(['orderNumber' => $orderNo]);

                /* start 临时限制 315订单无法取消 */
                $orderCreateUnixtime = $order->getCreateTime(true);
                if($orderCreateUnixtime >= strtotime('2018-03-14 00:00:00') &&
                    $orderCreateUnixtime <= strtotime('2018-03-16 23:59:59')){
                    $this->addError('cancelOrders', 3380);
                    return false;
                }
                /* end 315订单无法取消 */

                /* 临时限制 订单内包含指定商品ID禁止取消订单 */
                foreach($order->getItems() as $item){
                    $productId = $item->getItem()->getProductId();
                    if($productId == 2150 || $productId == 2180){
                        $this->addError('cancelOrders', 3242);
                        return false;
                    }
                }
                /* end 临时限制 */
                //临时功能：拼团；订单被记录为拼团订单则不允许取消
                if(\common\ActiveRecord\ActivityGpubsGroupDetailAR::findOne(['order_number' => $orderNo])){
                    $this->addError('cancelOrders', 3246);
                    return false;
                }
                /* Start 取消订单变更：T恤订单禁止取消，其他供应商允许取消 */
                /* 2018-07-24悠耐无法取消 */
                if($order->supplierId == 10 || $order->supplierId == 37){
                    $this->addError('cancelOrders', 3242);
                    return false;
                }
                /* End 取消订单变更：T恤订单禁止取消，其他供应商允许取消 */
                if($order->status != $order::STATUS_UNPAID && $order->status != $order::STATUS_UNDELIVER){
                    $this->addError('cancelOrders', 3202);
                    return false;
                }
                /*如果使用优惠券，不允许取消订单*/
                if($order->status == $order::STATUS_UNDELIVER && $order->getTrade()->getCouponRecord()){
                    $this->addError('cancelOrders', 3298);
                    return false;
                }

                //临时功能：领水活动；如果该订单有活动商品则不允许取消
                if(\common\ActiveRecord\CustomUserActivityLimitAR::findOne(['order_id' => $order->id])){
                    $this->addError('cancelOrders', 3242);
                    return false;
                }
                //临时功能结束

                //临时功能：拼团；订单被记录为拼团订单则不允许取消
                if(\common\ActiveRecord\ActivityGroupbuyOrderAR::findOne(['order_id' => $order->id])){
                    $this->addError('cancelOrders', 3246);
                    return false;
                }
                //临时功能：拼团；订单被记录为拼团订单则不允许取消
                if(\common\ActiveRecord\ActivityGpubsGroupDetailAR::findOne(['detail_number' => $order->orderNumber])){
                    $this->addError('cancelOrders', 3246);
                    return false;
                }
                //临时功能结束

                $orders[] = $order;
            }
            foreach($orders as $order){
                //取消订单同时取消定制订单
                if($customization = $order->getCustomization()){
                    //定制订单接单后不能取消
                    if($customization->getStatus() >= OrderCustomization::STATUS_IN_PRODUCE){
                        $this->addError('cancelOrders', 3203);
                        return false;
                    } else {
                        $customization->cancelOrder();
                    }
                }
                OrderHandler::cancel($order);
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('cancelOrders', 3201);
            return false;
        }
    }

    public static function getOrderQuantity(){
        $quantity = Yii::$app->CustomUser->order->quantity;
        return [
            'unpaid' => $quantity[ORDER::STATUS_UNPAID] ?? 0,
            'undeliver' => $quantity[ORDER::STATUS_UNDELIVER] ?? 0,
            'delivered' => $quantity[ORDER::STATUS_DELIVERED] ?? 0,
            'confirmed' => $quantity[ORDER::STATUS_CONFIRMED] ?? 0,
            'canceled' => $quantity[ORDER::STATUS_CANCELED] ?? 0,
            'closed' => $quantity[ORDER::STATUS_CLOSED] ?? 0,
        ];
    }

    public function payOrders(){
        $orders = array_map(function($orderNo){
            return new Order(['orderNumber' => $orderNo]);
        }, $this->orders_no);
        foreach($orders as $order){
            if($order->status != $order::STATUS_UNPAID){
                $this->addError('payOrders', 3155);
                return false;
            }
        }
        $totalFee = array_sum(array_map(function($order){
            return $order->totalFee;
        }, $orders));
        if(!PaymentMethod::canPay($this->payment, (float)$totalFee)){
            $this->addError('payOrder', 3153);
            return false;
        }
        $paymentMethod = new PaymentMethod(['method' => $this->payment]);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $trade = TradeHandler::createOrdersTrade($orders, $paymentMethod);
            if($trade->needRecharge){
                $rechargeApply = CustomRechargeApplyHandler::create($trade->totalFee, $paymentMethod, $trade);
                $callBack = ['url' => $rechargeApply->generateRechargeUrl()];
            }else{
                if(Yii::$app->CustomUser->wallet->pay($trade)){
                    $q = (new UrlParamCrypt)->encrypt($trade->totalFee);
                    $callBack = ['url' => Url::to(['/trade/balance', 'q' => $q])];
                }else{
                    throw new \Exception;
                }
            }
            $transaction->commit();
            return $callBack;
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('payOrders', 3154);
            return false;
        }
    }

    public function confirmOrder(){
        $order = new Order(['orderNumber' => $this->no]);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(!$order->setStatus(Order::STATUS_CONFIRMED))throw new \Exception;
            $transaction->commit();
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('confirmOrder', 3131);
            return false;
        }
        return $order->receiveTime;
    }

    public function getOrderInfo(){
        $order = new Order(['orderNumber' => $this->no]);
        $emptyFunc = function($data){
            return empty($data) ? '' : $data;
        };
        return Handler::getMultiAttributes($order, [
            'storename',
            'supplier' => 'supplierId',
            'total_fee' => 'totalFee',
            'items_fee' => 'itemsFee',
            'coupon_rmb' => 'couponRmb',
            'refund_rmb' => 'refundRmb',
            'coupon_info' => 'ticket',
            'status',
            'express_corporation' => 'expressCorpName',
            'express_number' => 'expressNo',
            'consignee',
            'address',
            'mobile',
            'postal_code' => 'postalCode',
            'create_time' => 'createTime',
            'pay_time' => 'payTime',
            'deliver_time' => 'deliverTime',
            'receive_time' => 'receiveTime',
            'cancel_time' => 'cancelTime',
            'close_time' => 'closeTime',
            'items',
            'pay_method' => 'payMethod',
            '_func' => [
                'expressCorporationName' => $emptyFunc,
                'expressNo' => $emptyFunc,
                'createTime' => $emptyFunc,
                'payTime' => $emptyFunc,
                'deliverTime' => $emptyFunc,
                'receiveTime' => $emptyFunc,
                'cancelTime' => $emptyFunc,
                'closeTime' => $emptyFunc,
                'payMethod' => function($id){
                    return \common\models\parts\trade\PaymentMethodList::queryMethodName($id);
                },
                'ticket' => function($ticket){
                    if($ticket){
                        $coupon = $ticket->coupon;
                        return [
                            'name' => $coupon->name,
                            'supplier' => $coupon->supplier ? $coupon->supplier->brandName : '',
                            'consumption_limit' => $coupon->consumptionLimit,
                            'discount' => $coupon->price,
                        ];
                    }else{
                        return [
                            'name' => '',
                            'supplier' => '',
                            'consumption_limit' => '',
                            'discount' => '',
                        ];
                    }
                },
                'items' => function($items){
                    return array_map(function($item){
                        return Handler::getMultiAttributes($item, [
                            'id',
                            'product_id' => 'item',
                            'title',
                            'attributes' => 'SKUAttributes',
                            'price',
                            'count',
                            'total_fee' => 'totalFee',
                            'image',
                            'comments',
                            '_func' => [
                                'item' => function($item){
                                    return $item->productId;
                                },
                                'image' => function($image){
                                    $ossImageHandlerObj = OSSImageHandler::load($image);
                                    $ossSize = $ossImageHandlerObj->resize(92,92);
                                    return $ossSize->apply() ? $ossSize->image->path : '';
                                },
                            ],
                        ]);
                    }, $items);
                },
            ],
        ]);
    }

    public function getList(){
        $status = $this->status === 0 ? null : (int)$this->status;
        $data = Yii::$app->CustomUser->order->provideOrders($status, $this->current_page, $this->page_size);
        $orders = array_map(function($one){
            return new Order(['id' => $one['id']]);
        }, $data->models);
        $emptyFunc = function($data){
            return empty($data) ? '' : $data;
        };
        return [
            'orders' => array_map(function($order)use($emptyFunc){
                return Handler::getMultiAttributes($order, [
                    'order_no' => 'orderNo',
                    'storename' => 'brandName',
                    'total_fee' => 'totalFee',
                    'items_fee' => 'itemsFee',
                    'status',
                    'express_corporation' => 'expressCorpName',
                    'express_number' => 'expressNo',
                    'create_time' => 'createTime',
                    'pay_time' => 'payTime',
                    'deliver_time' => 'deliverTime',
                    'receive_time' => 'receiveTime',
                    'cancel_time' => 'cancelTime',
                    'close_time' => 'closeTime',
                    'items',
                    '_func' => [
                        'expressCorporationName' => $emptyFunc,
                        'expressNumber' => $emptyFunc,
                        'payTime' => $emptyFunc,
                        'deliverTime' => $emptyFunc,
                        'receiveTime' => $emptyFunc,
                        'cancelTime' => $emptyFunc,
                        'closeTime' => $emptyFunc,
                        'items' => function($items){
                            return array_map(function($item){
                                return Handler::getMultiAttributes($item, [
                                    'id',
                                    'title',
                                    'product_id' => 'item',
                                    'attributes' => 'SKUAttributes',
                                    'price',
                                    'count',
                                    'total_fee' => 'totalFee',
                                    'image',
                                    'comments',
                                    '_func' => [
                                        'item' => function($item){
                                            return $item->productId;
                                        },
                                        'image' => function($image){
                                            $ossImageHandlerObj = OSSImageHandler::load($image);
                                            $ossSize = $ossImageHandlerObj->resize(87,87);
                                            return $ossSize->apply() ? $ossSize->image->path : '';
                                        },
                                       
                                    ],
                                ]);
                            }, $items);
                        }
                    ],
                ]);
            }, $orders),
            'count' => $data->count,
            'total_count' => $data->totalCount,
        ];
    }

    //临时功能
    public function getAccountOrders(){
        $userAccount = new \common\ActiveRecord\CustomUserAR;
        if($CustomUserAR = $userAccount::findOne(['account' => $this->user_account])){
            $orderAR = new \common\ActiveRecord\OrderAR;
            if($orderData = $orderAR::find()->select(['order_number', 'supply_user_id'])->where(['custom_user_id' => $CustomUserAR->id, 'status' => 1])->all()){
                return array_map(function($one){
                    return \common\components\handler\Handler::getMultiAttributes($one, [
                        'order_no' => 'order_number',
                        'supplier_account' => 'supply_user_id',
                        '_func' => [
                            'supply_user_id' => function($id){
                                return \common\ActiveRecord\SupplyUserAR::findOne($id)->account;
                            }
                        ],
                    ]);
                }, $orderData);
            }else{
                return [];
            }
        }else{
            return [];
        }
    }
}
