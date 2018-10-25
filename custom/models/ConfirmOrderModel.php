<?php
namespace custom\models;

use common\ActiveRecord\CouponRecordAR;
use common\models\parts\coupon\CouponRecord;
use Yii;
use common\models\Model;
use custom\models\parts\UrlParamCrypt;
use custom\models\parts\trade\PaymentMethod;
use common\models\parts\trade\PaymentMethodList;
use custom\components\handler\OrderHandler;
use custom\components\handler\TradeHandler;
use common\models\parts\Address;
use custom\components\handler\CustomRechargeApplyHandler;
use yii\helpers\Url;
use custom\models\parts\CouponOperation;
use common\components\handler\Handler;
use common\models\parts\Product;
use custom\models\parts\ItemInCart;
use custom\models\parts\temp\OrderReduction\ActivityReduction;

class ConfirmOrderModel extends Model{

    const SCE_GET_LIST = 'get_list';
    const SCE_GENERATE_TRADE = 'generate_trade';
    const SCE_GET_TICKETS = 'get_tickets';
    const SCE_GET_SUITABLE_TICKETS = 'get_suitable_tickets';
    const SCE_GET_REDUCTION = 'get_reduction';//临时功能-满减

    public $q;
    public $address;
    public $payment;
    public $items;


    /*
     * Mod By:Jiangyi
     * Date:2017/3/31
     * Desc:记录用户备注信息
     */

    public $comments;

    public function scenarios(){
        return [
            self::SCE_GET_LIST => [
                'q',
            ],
            self::SCE_GENERATE_TRADE => [
                'q',
                'address',
                'payment',
                'items',
            ],
            self::SCE_GET_TICKETS => [
                'q',
            ],
            self::SCE_GET_SUITABLE_TICKETS => [
                'q',
            ],
            self::SCE_GET_REDUCTION => [
                'q',
            ],
        ];
    }

    public function rules(){
        return [
            [
                //默认为空数组
                ['comments'],
                'default',
                'value'=>[],
            ],
            [
                ['ticket'],
                'default',
                'value'=>0,
            ],
            [
                ['q', 'address', 'payment', 'items'],
                'required',
                'message' => 9001,
            ],
            [
                ['q'],
                'common\validators\order\QValidator',
                'userId' => Yii::$app->user->id,
                'validateStock' => true,
                'outOfStock' => 3102,
                'message' => 3101,
            ],
            [
                ['address'],
                'common\validators\order\AddressValidator',
                'userId' => Yii::$app->user->id,
            ],
            [
                ['payment'],
                'common\validators\order\PaymentValidator',
                'paymentMethod' => new \custom\models\parts\trade\PaymentMethod,
                'message' => 3103,
            ],
            [
                ['items'],
                'common\validators\order\ItemsValidator',
                'q' => $this->q,
                'message' => 3106,
            ],
        ];
    }


    //获取验证状态
    protected  function getCouponRecordStatus(){
        return CouponRecord::STATUS_ACTIVE;
    }

    //获取用户ID
    protected  function getCustomerId(){
        return Yii::$app->user->id;
    }

    public static function getPaymentMethod(){
        $paymentMethod = new PaymentMethodList(['method' => [
            PaymentMethod::METHOD_BALANCE,
            PaymentMethod::METHOD_ALIPAY,
            PaymentMethod::METHOD_GATEWAY_PERSON,
            PaymentMethod::METHOD_GATEWAY_CORP,
            PaymentMethod::METHOD_ABCHINA_GATEWAY,
        ]]);
        return array_values($paymentMethod->paymentMethod);
    }

    public function generateTrade(){
        $itemsId = (new UrlParamCrypt)->decrypt($this->q);
        $items = Yii::$app->CustomUser->cart->getItems($itemsId);
        if(!PaymentMethod::canPay($this->payment)){
            $this->addError('generateTrade', 3104);
            return false;
        }

        //临时限制：个人网关支付只允许level 4用户使用
        if($this->payment == PaymentMethod::METHOD_GATEWAY_PERSON){
            if(Yii::$app->CustomUser->CurrentUser->level != 4){
                $this->addError('generateTrade', 3104);
                return false;
            }
        }
        //临时限制结束

        $paymentMethod = new PaymentMethod(['method' => $this->payment]);
        $transaction = Yii::$app->db->beginTransaction();

        try{

            //临时功能：限制PRODUCT购买数量；下单后取消订单、退款均不改变已购买数量
            //限购PRODUCT可配置：\custom\models\parts\temp\OrderLimit\product.php
            //文件不存在可自己创建，配置文件直接返回数组，键名PRODUCT ID，键值限购数量
            $productLimit = new \custom\models\parts\temp\OrderLimit\ProductLimit;
            if($productLimit->hasLimitProduct){
                foreach($items as $item){
                    if($productLimit->isLimitProduct($item)){
                        if($productLimit->validateProductQuantity($item)){
                            $productLimit->addBoughtProduct($item);
                        }else{
                            $transaction->rollBack();
                            return [
                                'error' => "商品[{$item->title}]属于限购商品，\n每人最多可购买[{$productLimit->getProductLimitQuantity($item)}]件，已购买[{$productLimit->hasBoughtQuantity($item)}]件，请返回购物车调整商品数量。",
                            ];
                        }
                    }
                }
            }
            //临时功能结束

            /**
             * 临时功能：限制PRODUCT购买账号
             * 可配置：\custom\models\parts\temp\OrderLimit\custom.php
             * 文件不存在可自己创建，配置文件直接返回数组，键名PRODUCT ID，键值为数组，数组成员为账号9位ID
             */
            $customLimit = new \custom\models\parts\temp\OrderLimit\CustomLimit;
            if($customLimit->hasLimitCustom){
                foreach($items as $item){
                    if(!$customLimit->validateCustom($item)){
                        $transaction->rollBack();
                        return [
                            'error' => "商品[{$item->title}]属于限购商品，仅允许指定账号购买。\n请返回购物车调整需下单的商品。",
                        ];
                    }
                }
            }
            //临时功能结束

            /**
             * 临时功能：限制PRODUCT购买时间
             * 可配置：\custom\models\parts\temp\OrderLimit\time.php
             * 文件不存在自己创建，配置文件直接返回数组，键名PRODUCT ID，键值为可购买时间。
             * 键值：string|integer|array
             * 当键值为array时可限制起始及结束时间，其他仅限制起始时间。
             * 时间可为字符串: Y-m-d H:i:s，亦可为时间戳。
             * e.g.
             * ```
             * return [
             *     1 => 1498842000, //unix time
             *     2 => '2017-07-01 01:00:00', //formative time
             *     3 => [1498842000, 1498843000], //限制起始及结束时间
             *     4 => ['2017-07-01 01:00:00', '2017-07-01 01:16:40'],
             *     5 => ['2017-07-01 01:00:00', 1498843000], //时间格式可混用
             *     6 => [1498842000], //仅限制起始时间
             * ];
             * ```
             */
            $timeLimit = new \custom\models\parts\temp\OrderLimit\TimeLimit;
            if($timeLimit->hasTimeLimit){
                foreach($items as $item){
                    if(!$timeLimit->validateTimeLimit($item)){
                        $transaction->rollBack();
                        $limit = $timeLimit->getTimeLimit($item, true);
                        $end = $limit['end'] === false ? '' : "，\n[{$limit['end']}]结束";
                        return [
                            'error' => "商品[{$item->title}]属于限购商品，\n仅允许在指定时间购买。\n可购买时间：[{$limit['start']}]起始{$end}。\n请返回购物车调整需下单的商品。",
                        ];
                    }
                }
            }
            //临时功能结束

            /**
             * 临时功能：领水活动，限制商品购买时间，购买数量，并生成领取码
             * 可配置：\custom\models\parts\temp\OrderLimit\activity.php
             * 文件不存在自己创建，配置文件直接返回数组，键名PRODUCT ID，键值为数组；
             * 键值数组配置：
             * ```
             * 'time' => [`unixtime for start date`, `unixtime for end date`], //限制只能在该时间段内才能下单
             * 'limit' => `integer`, //限制数量
             * ```
             *
             * 完整配置示例：
             * ```
             * return [
             *     41 => [
             *         'time' => [
             *             1483200000,
             *             1498838400,
             *         ],
             *         'limit' => 1,
             *     ],
             * ];
             */
            $activityLimit = new \custom\models\parts\temp\OrderLimit\ActivityLimit;
            if($activityLimit->hasActivity){
                $hasActivity = false;
                foreach($items as $item){
                    if($activityLimit->isLimitProduct($item)){
                        if(!$activityLimit->validateTime($item)){
                            $transaction->rollBack();
                            $limitTime = $activityLimit->getLimitTime($item);
                            return [
                                'error' => "商品[{$item->title}]属于限购商品，仅允许在{$limitTime['start']}至{$limitTime['end']}购买。\n请返回购物车调整需下单的商品。",
                            ];
                        }
                        if(!$activityLimit->validateProductQuantity($item)){
                            $transaction->rollBack();
                            $limitQuantity = $activityLimit->getLimitQuantity($item);
                            $hasBoughtQuantity = $activityLimit->getHasBoughtQuantity($item);
                            return [
                                'error' => "商品[{$item->title}]属于限购商品，仅允许购买[{$limitQuantity}]件，您已购买[{$hasBoughtQuantity}]件。请返回购物车调整商品数量。",
                            ];
                        }
                        if(isset($activityInsertId[$item->supplier])){
                            $activityInsertId[$item->supplier] = array_merge($activityInsertId[$item->supplier], $activityLimit->addBought($item));
                        }else{
                            $activityInsertId[$item->supplier] = $activityLimit->addBought($item);
                        }
                        $hasActivity = true;
                    }
                }
            }else{
                $hasActivity = false;
            }
            //临时功能结束

            /**
             * 临时功能-满减
             * 配置文件：custom/models/parts/temp/OrderReduction/activity.php
             * 没有则手动创建，格式：
             * ```php
             * return [
             *     'time' => [
             *         'from' => '2017-12-01 00:00:01',
             *         'to' => '2017-12-31 00:12:01',
             *     ],
             *     'supplier' => [
             *         `supplier_id` => [`coupon_id1`, `coupon_id2`],
             *     ],
             * ];
             * ```
             * 在活动时间内，当前下单商品满足以下条件则自动推送并使用优惠券：
             * 1、该店铺在配置文件中
             * 2、非订制商品满足优惠券使用条件
             */
            $activityReduction = new ActivityReduction;
            if($coupons = $activityReduction->matchCoupon($itemsId, false)){
                $couponTransaction = Yii::$app->db->beginTransaction();
                try{
                    foreach($coupons as $coupon){
                        if($coupon->sendForCustomers([Yii::$app->user->identity->account])){
                            $recordId = Yii::$app->RQ->AR(new CouponRecordAR)->scalar([
                                'select' => ['id'],
                                'where' => [
                                    'coupon_id' => $coupon->id,
                                    'custom_user_id' => Yii::$app->user->id,
                                    'status' => CouponRecord::STATUS_ACTIVE,
                                ],
                                'orderBy' => [
                                    'id' => SORT_DESC,
                                ],
                            ]);
                            $this->items[$coupon->supplier->id][Product::TYPE_STANDARD]['ticket'] = $recordId;
                        }else{
                            throw new \Exception;
                        }
                    }
                    $couponTransaction->commit();
                }catch(\Exception $e){
                    $couponTransaction->rollBack();
                }
            }
            //临时功能结束

            //if(!$orders = OrderHandler::multiCreate($items,Yii::$app->CustomUser->address->getList($this->address)))throw new \Exception;
            if(!$orders = $this->multiCreate($this->items, Yii::$app->CustomUser->address->getList($this->address)))throw new \Exception;

            //临时功能：领水活动；如果当前下单有活动商品则记录订单ID
            if($hasActivity){
                foreach($orders as $order){
                    if(in_array($order->getSupplierId(), $activityLimit->getSuppliers()) && isset($activityInsertId[$order->getSupplierId()]) && !$order->getCustomization()){
                        $activityLimit->setOrderId($activityInsertId[$order->getSupplierId()], $order);
                    }
                }
            }
            //临时功能结束

            if(!$trade = TradeHandler::createOrdersTrade($orders, $paymentMethod, false))throw new \Exception;

            if(!PaymentMethod::canPay($this->payment, $trade->totalFee)){
                $transaction->rollBack();
                $this->addError('generateTrade', 3301);
                return false;
            }

            if($trade->needRecharge){
                if(!$rechargeApply = CustomRechargeApplyHandler::create($trade->totalFee, $paymentMethod, $trade))throw new \Exception;
                if($rechargeUrl = $rechargeApply->generateRechargeUrl()){
                    $callBack = ['url' => $rechargeUrl];
                }else{
                    throw new \Exception('creating recharge url failed');
                }
            }else{
                if(Yii::$app->CustomUser->wallet->pay($trade)){
                    $q = (new UrlParamCrypt)->encrypt($trade->totalFee);
                    $callBack = ['url' => Url::to(['/trade/balance', 'q' => $q,'id'=>$trade->id])];
                }else{
                    throw new \Exception;
                }
            }

            $transaction->commit();
        }catch(\Exception $e){
            $transaction->rollBack();
            Yii::error($e, __METHOD__);
            if(Yii::$app->id == 'app-mobile'){
                //跟踪生成订单失败请求头
                try{
                    Yii::$app->RQ->AR(new \common\ActiveRecord\LoginFailureLogAR)->insert([
                        'custom_user_id' => Yii::$app->user->id,
                        'request_header' => $e->getMessage() . '  ' . $e->getFile() . '  ' . $e->getLine(),
                        'wechat_code' => Yii::$app->session->get('__wechat_code', '-1'),
                        'wechat_openid' => Yii::$app->session->get('__wechat_public_openid', '-1'),
                        'tag' => '3',
                    ]);
                }catch(\Exception $e){}
                //结束
            }
            $this->addError('generateTrade', 3105);
            return false;
        }
        return $callBack;
    }

    public function getList(){
        $itemsId = (new UrlParamCrypt)->decrypt($this->q);
        $items = Yii::$app->CustomUser->cart->getItemsGroupByOrders($itemsId);
        return array_map(function($item){
            $item['supplier_id'] = $item['supplier']->id;
            $item['supplier'] = $item['supplier']->brandName;
            $item['items'] = array_map(function($splitItems){
                return array_map(function($one){
                    return [
                        'title' => $one->title,
                        'product_id' => $one->productId,
                        'product_sku_id'=>$one->id,
                        'image' => $one->mainImage->path,
                        'attributes' => array_map(function($attribute){
                            $attribute['selected_option'] = $attribute['selectedOption'];
                            unset($attribute['selectedOption']);
                            return $attribute;
                        }, $one->attributes),
                        'price' => $one->price,
                        'count' => $one->count,
                    ];
                }, $splitItems);
            }, $item['items']);
            return $item;
        }, $items);
    }

    public function getSuitableTickets(){
        $itemsId = (new UrlParamCrypt)->decrypt($this->q);
        $items = Yii::$app->CustomUser->cart->getItemsGroupByOrders($itemsId);
        $splitItems = $this->generateSplitItems($items);
        if($availableTickets = $this->getUserAvailableTickets()){
            $couponOperation = new CouponOperation([
                'items' => $splitItems,
                'tickets' => $availableTickets,
            ]);
            return array_map(function($supplier){
                return array_map(function($productType){
                    return array_map(function($items){
                        if(is_array($items)){
                            return array_map(function($item){
                                return $item->id;
                            }, $items);
                        }else{
                            return $items->id;
                        }
                    }, $productType);
                }, $supplier);
            }, $couponOperation->suitableTickets);
        }else{
            return true;
        }
    }

    public function getTickets(){
        $itemsId = (new UrlParamCrypt)->decrypt($this->q);
        $items = Yii::$app->CustomUser->cart->getItemsGroupByOrders($itemsId);
        $splitItems = $this->generateSplitItems($items);
        if($availableTickets = $this->getUserAvailableTickets()){
            $couponOperation = new CouponOperation([
                'items' => $splitItems,
                'tickets' => $availableTickets,
            ]);
            return array_map(function($tickets){
                return array_map(function($ticket){
                    return Handler::getMultiAttributes($ticket, [
                        'id',
                        'name' => 'coupon',
                        'start_time' => 'coupon',
                        'end_time' => 'coupon',
                        'price' => 'coupon',
                        'limit_price' => 'coupon',
                        'brand_name' => 'coupon',
                        '_func' => [
                            'coupon' => function($coupon, $name){
                                switch($name){
                                    case 'name':
                                        return $coupon->name;

                                    case 'start_time':
                                        return $coupon->startTime;

                                    case 'end_time':
                                        return $coupon->endTime;

                                    case 'price':
                                        return $coupon->price;

                                    case 'limit_price':
                                        return $coupon->consumptionLimit;

                                    case 'brand_name':
                                        try{
                                            return $coupon->supplier->brandName;
                                        }catch(\Exception $e){
                                            return '';
                                        }

                                    default:
                                        return '';
                                }
                            }
                        ],
                    ]);
                }, $tickets);
            }, $couponOperation->devideTickets);
        }else{
            return [
                'valid' => [],
                'invalid' => [],
            ];
        }
    }

    private function generateSplitItems($items){
        $splitItems = [];
        foreach($items as $k => $supplierAndItems){
            $splitItems[$supplierAndItems['supplier']->id] = $supplierAndItems['items'];
        }
        return $splitItems;
    }

    private function getUserAvailableTickets(){
        return Yii::$app->CustomUser->CurrentUser->availableTickets;
    }

    private function multiCreate($items, $address){
        $orders = [];
        try{
            foreach($this->items as $supplier){
                if(isset($supplier[Product::TYPE_STANDARD]) && count($supplier[Product::TYPE_STANDARD]) > 1){
                    if(!$standardOrder = $this->createStandardOrder($supplier[Product::TYPE_STANDARD], $address))throw new \Exception;
                    $orders = array_merge($orders, [$standardOrder]);
                }
                if(isset($supplier[Product::TYPE_CUSTOMIZATION])){
                    $orders = array_merge($orders, $this->createCustomOrders($supplier[Product::TYPE_CUSTOMIZATION], $address));
                }
            }
        }catch(\Exception $e){
            return false;
        }
        if(!$orders)return false;
        return $orders;
    }

    private function createStandardOrder($standardItems, $address){
        $ticket = $standardItems['ticket'] ? new CouponRecord(['id' => $standardItems['ticket']]) : false;
        unset($standardItems['ticket']);
        $items = [];
        foreach($standardItems as $itemId => $comment){
            $items[] = new ItemInCart([
                'id' => $itemId,
                'comments' => $comment,
            ]);
        }
        if(!$order = OrderHandler::create($items, $address))return false;
        if($ticket){
            $order->useCoupon($ticket);
        }
        return $order;
    }

    private function createCustomOrders($customItems, $address){
        $allOrders = [];
        foreach($customItems as $itemId => $commentAndTicket){
            $comments = array_column($commentAndTicket, 'comment');
            if($tickets = array_filter(array_column($commentAndTicket, 'ticket'))){
                $tickets = array_map(function($ticketId){
                    return new CouponRecord([
                        'id' => $ticketId,
                    ]);
                }, $tickets);
            }else{
                $tickets = false;
            }
            $item = new ItemInCart([
                'id' => $itemId,
                'comments' => $comments,
            ]);
            if(!$orders = OrderHandler::createCustomizeOrders($item, $address))return false;
            if($tickets){
                foreach($tickets as $k => $ticket){
                    $orders[$k]->useCoupon($ticket);
                }
            }
            $allOrders = array_merge($allOrders, $orders);
        }
        return $allOrders;
    }

    //临时功能-满减
    public function getReduction(){
        $itemsId = (new UrlParamCrypt)->decrypt($this->q);
        $activityReduction = new ActivityReduction;
        if($coupons = $activityReduction->matchCoupon($itemsId, false)){
            $rmb = 0;
            foreach($coupons as $coupon){
                $rmb += $coupon->price;
            }
            return ['reduce_rmb' => $rmb];
        }else{
            return ['reduce_rmb' => 0];
        }
    }
}
