<?php
namespace custom\modules\gpubs\models;

use Yii;
use common\models\Model;
use common\models\parts\gpubs\GpubsProduct;
use common\ActiveRecord\ActivityGpubsProductSkuAR;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\ActivityGpubsGroupDetailAR;
use common\ActiveRecord\ActivityGpubsGroupTicketAR;
use common\ActiveRecord\ActivityGpubsGroupDetailPickLogAR;
use common\models\parts\gpubs\GpubsGroup;
use common\components\handler\Handler;
use yii\data\ActiveDataProvider;
use common\models\parts\gpubs\GpubsGroupDetail;
use common\models\parts\gpubs\GpubsGroupTicket;
use common\models\parts\Item;
use common\models\parts\Order;
use common\models\parts\Express;
use common\ActiveRecord\ProductAR;


class ApiModel extends Model{

    const SCE_GET_PRODUCT = 'get_product';
    const SCE_GET_GPUBS_TIME = 'get_gpubs_time';
    const SCE_GET_GROUP = 'get_group';
    const SCE_GET_ORDER_LIST = 'get_order_list';
    const SCE_GET_ORDER_DETAIL = 'get_order_detail';
    const SCE_GET_JOIN_FAILED_LIST = 'get_join_failed_list';
    const SCE_ACTIVITY_GROUP_LIST = 'activity_group_list';
    const SCE_ACTIVITY_GROUP_DETAIL = 'activity_group_detail';
    const SCE_GET_TRADE = 'get_trade';

    const CACHE_GROUP_INFO_KEY          = 'activity_group_list';
    const CACHE_GROUP_INFO_DURATION     = 10; #缓存时间根据活动具体设置

    const TICKET_STATUS_ALL = 2;
    const DETAIL_TYPE_ALL = 0;
    const DETAIL_STATUS_ALL = -1;

    public $product_id;
    public $group_id;
    public $status;
    public $current_page;
    public $page_size;
    public $order_id;
    public $is_join;
    public $activity_id;
    public $gpubs_type;
    public $gpubs_rule_type;
    public $product_name;//搜索条件

    private $rule_type_flag = false;

    public function scenarios(){
        return [
            self::SCE_GET_PRODUCT => [
                'product_id',
            ],
            self::SCE_GET_GPUBS_TIME => [
                'product_id',
            ],
            self::SCE_GET_GROUP => [
                'group_id',
            ],
            self::SCE_GET_ORDER_LIST => [
                'status',
                'current_page',
                'page_size',
                'gpubs_type',
                'product_name',
            ],
            self::SCE_GET_ORDER_DETAIL => [
                'order_id',
            ],
            self::SCE_GET_JOIN_FAILED_LIST => [
                'is_join',
                'current_page',
                'page_size',
                'product_name',
            ],

            self::SCE_ACTIVITY_GROUP_LIST => [
                'activity_id',
            ],
            self::SCE_ACTIVITY_GROUP_DETAIL => [
                'group_id'
            ],

            self::SCE_GET_TRADE => [
                'trade_id',
            ]

        ];
    }

    public function rules(){
        return [
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
                ['gpubs_type'],
                'default',
                'value' => 0,
            ],
            [
                ['product_id', 'group_id', 'status', 'current_page', 'page_size', 'order_id', 'is_join','gpubs_type','trade_id'],
                'required',
                'message' => 9001,
            ],
            [
                ['group_id','activity_id'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['group_id'],
                'exist',
                'targetClass' => 'common\ActiveRecord\ActivityGpubsGroupAR',
                'targetAttribute' => ['group_id' => 'id'],
                'message' => 9002,
            ],
            [
                ['status'],
                'in',
                'range' => [
                    -1,
                    GpubsGroupDetail::STATUS_CANCELED,
                    GpubsGroupDetail::STATUS_WAIT,
                    GpubsGroupDetail::STATUS_UNPICK,
                    GpubsGroupDetail::STATUS_PICKED_PART,
                    GpubsGroupDetail::STATUS_PICKED_ALL,
                ],
                'message' => 9002,
            ],
            [
                ['is_join'],
                'in',
                'range' => [
                    GpubsGroupTicket::JOIN_WAIT,
                    GpubsGroupTicket::JOIN_FAILED,
                    GpubsGroupTicket::JOIN_SUCCESS,
                    self::TICKET_STATUS_ALL,
                ],
                'message' => 9002,
            ],
            [
                ['current_page', 'page_size'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['order_id'],
                'exist',
                'targetClass' => 'common\ActiveRecord\ActivityGpubsGroupDetailAR',
                'targetAttribute' => ['order_id' => 'id'],
                'message' => 9002,
            ],
            [
                ['trade_id'],
                'exist',
                'targetClass' => 'common\ActiveRecord\ActivityGpubsGroupTicketAR',
                'targetAttribute' => ['trade_id' => 'id'],
                'message' => 9002,
            ],
            [
                ['product_name'],
                'string',
                'message' => 9002,
            ],
        ];
    }

    public function getJoinFailedList(){
        if($this->is_join == self::TICKET_STATUS_ALL){
            $provider = new ActiveDataProvider([
                'query' => ActivityGpubsGroupTicketAR::find()->
                select(['id'])->
                where([
                    'custom_user_id' => Yii::$app->user->id,
                ])->
                asArray(),
                'pagination' => [
                    'page' => $this->current_page - 1,
                    'pageSize' => $this->page_size,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'create_unixtime' => SORT_DESC,
                    ],
                ],
            ]);
        }else{
            if (!empty($this->product_name)){
                $result =  ActivityGpubsGroupTicketAR::find()->alias('t')->
                        select(['t.id'])->
                        where([
                            't.custom_user_id' => Yii::$app->user->id,
                            't.is_join' => $this->is_join,
                        ])->leftJoin(ProductAR::tableName() . ' p', 'p.id = t.product_id')
                        ->andWhere(['like','p.title',$this->product_name])
                        ->asArray();
            }else{
                $result =  ActivityGpubsGroupTicketAR::find()->
                            select(['id'])->
                            where([
                                'custom_user_id' => Yii::$app->user->id,
                                'is_join' => $this->is_join,
                            ])->
                            asArray();
            }
            $provider = new ActiveDataProvider([
                'query' => $result,
                'pagination' => [
                    'page' => $this->current_page - 1,
                    'pageSize' => $this->page_size,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'create_unixtime' => SORT_DESC,
                    ],
                ],
            ]);
        }
        return Handler::getMultiAttributes($provider, [
            'count',
            'total_count' => 'totalCount',
            'data' => 'models',
            '_func' => [
                'models' => function($models){
                    return array_map(function($ticketId){
                        $ticket = new GpubsGroupTicket([
                            'id' => $ticketId,
                        ]);
                        $res = Handler::getMultiAttributes($ticket, [
                            'id',
                            'group',
                            'product' => 'product_sku_id',
                            'quantity',
                            'price',
                            'total_fee',
                            'create_datetime',
                            'create_unixtime',
                            'pay_datetime',
                            'pay_unixtime',
                            'is_join',
                            'status',
                            '_func' => [
                                'id' => function($id){
                                    return $id['id'];
                                },
                                'group' => function($group){
                                    return Handler::getMultiAttributes($group, [
                                        'id',
                                        'group_number' => 'groupNumber',
                                        'group_start_datetime',
                                        'group_start_unixtime',
                                        'group_end_datetime',
                                        'group_end_unixtime',
                                        'group_establish_datetime',
                                        'group_establish_unixtime',
                                        'group_cancel_datetime',
                                        'group_cancel_unixtime',
                                        'target_quantity',
                                        'present_quantity',
                                        'full_address',
                                        'consignee',
                                        'mobile',
                                        'status',
                                        'gpubs_type',
                                        'gpubs_rule_type',
                                        'min_quantity_per_group'=>'id',
                                        'min_member_per_group' => 'id',
                                        'min_quanlity_per_member_of_group' => 'id',
                                        '_func' => [
                                            'id' => function($callback, $alias) use ($group) {
                                                switch($alias){
                                                    case 'min_quantity_per_group':
                                                        return $group->gpubsProduct->min_quantity_per_group;
                                                        break;

                                                    case 'min_member_per_group':
                                                        return $group->gpubsProduct->min_member_per_group;
                                                        break;

                                                    case 'min_quanlity_per_member_of_group':
                                                        return $group->gpubsProduct->min_quantity_per_member_of_group;
                                                        break;

                                                    default:
                                                        return $callback;
                                                }
                                            },
                                        ]
                                    ]);
                                },
                                'product_sku_id' => function($id){
                                    $item = new Item([
                                        'id' => $id,
                                    ]);
                                    return Handler::getMultiAttributes($item, [
                                        'product_id'=>'productId',
                                        'title',
                                        'image' => 'mainImage',
                                        'sku_attributes' => 'attributes',
                                        'brand_name' => 'supplier',
                                        '_func' => [
                                            'mainImage' => function($image){
                                                return $image->path;
                                            },
                                            'supplier' => function($supplierId){
                                                return (new \common\models\parts\Supplier(['id' => $supplierId]))->brandName;
                                            },
                                        ],
                                    ]);
                                },
                            ],
                        ]);
                        $res['product']['quantity'] = $res['quantity'];
                        $res['product']['price'] = $res['price'];
                        $res['product']['total_fee'] = $res['total_fee'];
                        return $res;
                    }, $models);
                },
            ],
        ]);
    }

    public function getOrderDetail(){
        $detail = new GpubsGroupDetail([
            'id' => $this->order_id,
        ]);
        if($detail->custom_user_id != Yii::$app->user->id){
            $this->addError('getOrderDetail', 9002);
            return false;
        }
        if(in_array($detail->status,[GpubsGroupDetail::STATUS_CANCELED,GpubsGroupDetail::STATUS_WAIT])){
            $this->addError('getOrderDetail', 9002);
            return false;
        }
        if($detail->gpubs_type == GpubsProduct::GPUBS_TYPE_DELIVER){
            $this->rule_type_flag = true;
        }
        $detailInfo = Handler::getMultiAttributes($detail, [
            'detail_number' => 'detailNumber',
            'join_datetime',
            'join_unixtime',
            'group_establish_datetime' => 'group',
            'group_establish_unixtime' => 'group',
            'picking_up_address' => 'group',
            'group_id' => 'group',
            'group_number' => 'group',
            'gpubs_type' => 'group',
            'gpubs_rule_type' => 'group',
            'quantity',
            'comment',
            'pay_method',
            'picking_up_log' => 'id',
            'picked_up_quantity'=> 'group',
            'picking_up_number'     => 'group',
            'min_quantity_per_group' => 'group',
            'min_member_per_group' => 'group',
            'spot_name' => 'group',
            'status' => 'group',
            'description' => 'group',
            'delivery_status' => 'group',
            'express_detial'  => 'group',
            'supply_user_id'  => 'group',
            '_func' => [
                'group' => function($group, $alias)use($detail){
                    $order = null;
                    if($this->rule_type_flag){
                        $order = new Order(['orderNumber' => $detail->order_number]);
                    }
                    if($alias == 'picking_up_address'){
                        if($this->rule_type_flag){
                            return [
                                'full_address'  => $order->getAddress(),
                                'consignee'     => $order->getConsignee(),
                                'mobile'        => $order->getMobile(),
                            ];
                        }else{
                            return Handler::getMultiAttributes($group, [
                                'full_address',
                                'consignee',
                                'mobile',
                            ]);
                        }
                    }elseif($alias == 'spot_name'){
                        if($this->rule_type_flag)return null;
                        return $group->spot_name;
                    }elseif($alias == 'status'){
                        if($this->rule_type_flag)return null;
                        return $detail->status;
                    }elseif($alias == 'delivery_status'){
                        if($this->rule_type_flag)return $order->getStatus();
                        return null;
                    }elseif($alias == 'picked_up_quantity'){
                        if($this->rule_type_flag)return null;
                        return $detail->picked_up_quantity;
                    }elseif($alias == 'picking_up_number'){
                        if($this->rule_type_flag)return null;
                        return $detail->picking_up_number;
                    }elseif($alias == 'express_detial'){
                        $express_detail = false;
                        if($this->rule_type_flag){
                            $express = new Express(['order' => $order]);
                            if($express->detail && is_array($express->detail)){
                                $express_detail = $express->detail;
                                $express_detail['name'] = Yii::$app->RQ->AR(new \common\ActiveRecord\ExpressCorporationAR)->scalar([
                                    'select' => ['name'],
                                    'where' => ['code' => $express_detail['com']],
                                ]);
                            }
                        }
                        return $express_detail;
                    }elseif($alias == 'group_id'){
                        return $group->id;
                    }elseif($alias == 'group_number') {
                        return $group->groupNumber;
                    }elseif($alias == 'min_quantity_per_group') {
                        return $group->gpubsProduct->min_quantity_per_group;
                    }elseif($alias == 'min_member_per_group') {
                        return $group->gpubsProduct->min_member_per_group;
                    }elseif($alias == 'description'){
                        return $group->gpubsProduct->description;
                    }else{
                        return $group->{$alias};
                    }
                },
                'id' => function($id)use($detail){
                    if($this->rule_type_flag)return [];
                    return ActivityGpubsGroupDetailPickLogAR::find()->
                    select([
                        'unpicked_quantity',
                        'quantity_to_pick',
                        'picking_up_number',
                        'picking_up_datetime',
                        'picking_up_unixtime',
                    ])->
                    where([
                        'activity_gpubs_group_detail_id' => $id,
                    ])->
                    orderBy([
                        'id' => SORT_ASC,
                    ])->
                    asArray()->
                    all();
                },
            ],
        ]);
        $productInfo['product'] = Handler::getMultiAttributes($detail, [
            'id' => 'product_id',
            'title' => 'product_title',
            'image' => 'product_image_filename',
            'sku' => 'skuAttributes',
            'product_sku_price',
            'quantity',
            'total_fee',
            'brand_name' => 'product',
            '_func' => [
                'product_image_filename' => function($name){
                    return Yii::$app->params['OSS_PostHost'] . '/' . $name;
                },
                'product' => function($product){
                    return $product->supplierObj->brandName;
                },

            ],
        ]);
        return array_merge($detailInfo, $productInfo);
    }

    public function getOrderList(){

        $where = [
            'custom_user_id' => Yii::$app->user->id,
        ];
        if($this->gpubs_type != self::DETAIL_TYPE_ALL) {
            $where['gpubs_type'] = $this->gpubs_type;
            $status = [
                GpubsGroupDetail::STATUS_UNPICK,
                GpubsGroupDetail::STATUS_PICKED_PART,
                GpubsGroupDetail::STATUS_PICKED_ALL,
            ];
        }else{
            $status = [
                GpubsGroupDetail::STATUS_CANCELED,
                GpubsGroupDetail::STATUS_WAIT,
                GpubsGroupDetail::STATUS_UNPICK,
                GpubsGroupDetail::STATUS_PICKED_PART,
                GpubsGroupDetail::STATUS_PICKED_ALL,
            ];
        }
        if($this->status != self::DETAIL_STATUS_ALL){
            $status = $this->status;
        }
        $where['status'] = $status;

        if($this->gpubs_type == self::DETAIL_TYPE_ALL) {
            $result =  ActivityGpubsGroupDetailAR::find()->
                select(['id'])->
                where($where)
                ->andFilterWhere(['like','product_title',$this->product_name])
                ->asArray();
            if(!in_array($status, [
                GpubsGroupDetail::STATUS_UNPICK,
                GpubsGroupDetail::STATUS_PICKED_PART,
                GpubsGroupDetail::STATUS_PICKED_ALL,
            ])){
                $result->groupBy('group_number');
            }
        }else{
            $result =  ActivityGpubsGroupDetailAR::find()->
                select(['id'])->
                where($where)
                ->andFilterWhere(['like','product_title',$this->product_name])
                ->asArray();
        }
        $provider = new ActiveDataProvider([
            'query' => $result,
            'pagination' => [
                'page' => $this->current_page - 1,
                'pageSize' => $this->page_size,
            ],
            'sort' => [
                'defaultOrder' => [
                    'join_unixtime' => SORT_DESC,
                ],
            ],
        ]);

        return Handler::getMultiAttributes($provider, [
            'data' => 'models',
            'count',
            'total_count' => 'totalCount',
            '_func' => [
                'models' => function($models){
                    return array_map(function($detailId){
                        $detail = new GpubsGroupDetail([
                            'id' => $detailId,
                        ]);
                        $detailInfo = Handler::getMultiAttributes($detail, [
                            'id',
                            'group',
                            'status',
                            'picking_up_number',
                            'detail_number' => 'detailNumber',
                            'join_datetime',
                            'join_unixtime',
                            'quantity',
                            'picked_up_quantity',
                            '_func' => [
                                'group' => function($group){
                                    return Handler::getMultiAttributes($group, [
                                        'id',
                                        'group_number' => 'groupNumber',
                                        'group_start_datetime',
                                        'group_start_unixtime',
                                        'group_end_datetime',
                                        'group_end_unixtime',
                                        'left_unixtime' => 'group_end_unixtime',
                                        'group_establish_datetime',
                                        'group_establish_unixtime',
                                        'group_cancel_datetime',
                                        'group_cancel_unixtime',
                                        'target_quantity',
                                        'present_quantity',
                                        'full_address',
                                        'consignee',
                                        'mobile',
                                        'status',
                                        'gpubs_type',
                                        'gpubs_rule_type',
                                        'min_quantity_per_group'=>'id',
                                        'min_member_per_group' => 'id',
                                        'min_quanlity_per_member_of_group' => 'id',
                                        '_func' => [
                                            'group_end_unixtime' => function($endTime, $alias){
                                                if($alias == 'left_unixtime'){
                                                    return $endTime - Yii::$app->time->unixTime;
                                                }else{
                                                    return $endTime;
                                                }
                                            },
                                            'id' => function($callback, $alias)use($group){
                                                switch($alias){
                                                    case 'min_quantity_per_group':
                                                        return $group->gpubsProduct->min_quantity_per_group;
                                                        break;

                                                    case 'min_member_per_group':
                                                        return $group->gpubsProduct->min_member_per_group;
                                                        break;

                                                    case 'min_quanlity_per_member_of_group':
                                                        return $group->gpubsProduct->min_quantity_per_member_of_group;
                                                        break;

                                                    default:
                                                        return $callback;
                                                }
                                            },
                                        ],
                                    ]);
                                },
                            ],
                        ]);
                        $productInfo['product'] = Handler::getMultiAttributes($detail, [
                            'product_id',
                            'title' => 'product_title',
                            'image' => 'product_image_filename',
                            'sku' => 'skuAttributes',
                            'product_sku_price',
                            'quantity',
                            'total_fee',
                            'brand_name' => 'product',
                            '_func' => [
                                'product_image_filename' => function($name){
                                    return Yii::$app->params['OSS_PostHost'] . '/' . $name;
                                },
                                'product' => function($product){
                                    return $product->supplierObj->brandName;
                                },
                            ],
                        ]);
                        return array_merge($detailInfo, $productInfo);
                    }, $models);
                },
            ],
        ]);
    }

    //用于获取商品活动时间。为了不影响原有逻辑新增接口
    public function getGpubsTime(){
        if($activityId = \common\ActiveRecord\ActivityGpubsProductAR::find()->
        select(['id'])->
        where(['product_id' => $this->product_id])->
        scalar()
        ){
            $activity = new GpubsProduct([
                'id' => $activityId,
            ]);
         
            return [
                'activity_start_datetime' => $activity->activity_start_datetime,
                'activity_start_unixtime' => $activity->activity_start_unixtime,
                'activity_end_datetime' => $activity->activity_end_datetime,
                'activity_end_unixtime' => $activity->activity_end_unixtime,
                'brand_name' => $activity->product->supplierObj->brandName,
                'expire_time' => $activity->activity_end_unixtime - time(),
            ];
        }else{
            return true;
        }
    }

    public function getProduct(){

        if($activityId = \common\ActiveRecord\ActivityGpubsProductAR::find()->
            select(['id'])->
            where(['product_id' => $this->product_id])->
            andWhere(['<', 'activity_start_unixtime', Yii::$app->time->unixTime])->
            andWhere(['>', 'activity_end_unixtime', Yii::$app->time->unixTime])->
            scalar()
        ){
            $activity = new GpubsProduct([
                'id' => $activityId,
            ]);
            $skus = $activity->getActivityProductSku();
            $skuStock = false;
            foreach ($skus as $sku) {
                if ( $sku['stock'] > 0 ){
                    $skuStock = true;
                }
            }
            if($skuStock){
                $minPrice = Yii::$app->RQ->AR(new ActivityGpubsProductSkuAR)->min([
                    'where' => [
                        'product_id' => $this->product_id,
                    ],
                ], 'price');
                $maxPrice = Yii::$app->RQ->AR(new ActivityGpubsProductSkuAR)->max([
                    'where' => [
                        'product_id' => $this->product_id,
                    ],
                ], 'price');
                return [
                    'min_quantity_per_group' => $activity->min_quantity_per_group,
                    'min_price' => $minPrice,
                    'max_price' => $maxPrice,
                    'status' => $activity->status,
                    'activity_start_datetime' => $activity->activity_start_datetime,
                    'activity_start_unixtime' => $activity->activity_start_unixtime,
                    'activity_end_datetime' => $activity->activity_end_datetime,
                    'activity_end_unixtime' => $activity->activity_end_unixtime,
                    'brand_name' => $activity->product->supplierObj->brandName,
                    'expire_time' => $activity->activity_end_unixtime - time(),
                    'sku' => $activity->activityProductSku,
                    'min_member_per_group' => $activity->min_member_per_group,
                    'min_quanlity_per_member_of_group' => $activity->min_quantity_per_member_of_group,
                    'gpubs_type' => $activity->gpubs_type,
                    'gpubs_rule_type' => $activity->gpubs_rule_type,
                    'description' => $activity->description,
                ];
            }else{
                return true;
            }
        }else{
            return true;
        }
    }

    public function getGroup(){
        $group = new GpubsGroup([
            'id' => $this->group_id,
        ]);
        return Handler::getMultiAttributes($group, [
            'owner_account' => 'custom_user_id',
            'group_start_datetime',
            'group_start_unixtime',
            'group_end_datetime',
            'group_end_unixtime',
            'group_establish_datetime',
            'group_establish_unixtime',
            'group_cancel_datetime',
            'group_cancel_unixtime',
            'target_quantity',
            'present_quantity',
            'full_address',
            'consignee',
            'mobile',
            'status',
            'gpubs_type',
            'gpubs_rule_type',
            'spot_name',
            'postal_code',
            '_func' => [
                'custom_user_id' => function($customUserId){
                    return CustomUserAR::findOne($customUserId)->account;
                },
            ],
        ]);
    }


    /**
     *根据活动id获取进行活动列表
     * @return array
     * [
        [
            business_area_id: integer,  //业务区域-省ID
            business_area_name: string,  //省名称
            group[
                status: integer //状态 1-待成团，2-已成团
                product_id: integer //活动商品ID
                group_number: integer,  //团编号
                consignee: string,  //团收货人
                mobile: string,  //团收货人联系手机
                img: string,  //团长头像
                address: string,  //团收货地址
                business_area[
                1: string,
                2: string,
                3: string,
                4: string,
                ]
            ]
        ]
      ]
     */
    public function activityGroupList(){

        if($group_res = Yii::$app->cache->get(self::CACHE_GROUP_INFO_KEY)){
            return $group_res;
        }

        $custom_user_group      =   [];
        $activity_group_list    =   [];
        try{
            $activity_group = Yii::$app->RQ->AR(new ActivityGpubsGroupAR())
                ->all([
                    'select' => ['id','group_number','consignee','mobile','full_address','status','custom_user_id'],
                    'where'  =>[
                        'activity_gpubs_product_id'=>$this->activity_id,
                        'status'=>[GpubsGroup::STATUS_WAIT,GpubsGroup::STATUS_ESTABLISH],
                    ]
                ]);

            $product_id = \common\ActiveRecord\ActivityGpubsProductAR::find()
                ->select(['product_id'])
                ->where(['id' => $this->activity_id])
                ->scalar();

            if(empty($activity_group) || empty($product_id))return $custom_user_group;

            foreach($activity_group as $k=>$group){
                $custom_user_obj    = new CustomUser(['id'=>$group['custom_user_id']]);
                $custom_user_group[$k]['business_area_id']          =   $custom_user_obj->businessTopAreaId;
                $custom_user_group[$k]['group']['product_id']       =   $product_id;
                $custom_user_group[$k]['group']['group_id']         =   $group['id'];
                $custom_user_group[$k]['group']['group_number']     =   $group['group_number'];
                $custom_user_group[$k]['group']['consignee']        =   $group['consignee'];
                $custom_user_group[$k]['group']['mobile']           =   $group['mobile'];
                $custom_user_group[$k]['group']['address']          =   $group['full_address'];
                $custom_user_group[$k]['group']['status']           =   $group['status'];
                $custom_user_group[$k]['group']['img']              =   $custom_user_obj->headerImg;
                $custom_user_group[$k]['group']['business_area']    =   $custom_user_obj->getBusinessArea([
                    $custom_user_obj->businessTopAreaId,
                    $custom_user_obj->businessSecondaryAreaId,
                    $custom_user_obj->businessTertiaryAreaId,
                    $custom_user_obj->businessQuaternaryAreaId,
                ]);
            }
            foreach ($custom_user_group as $key=>$value){
                $activity_group_list[$value['business_area_id']]['business_area_id']      =   $value['business_area_id'];
                $activity_group_list[$value['business_area_id']]['business_area_name']    =   $value['group']['business_area'][1] ?? '';
                $activity_group_list[$value['business_area_id']]['group'][$key]           =   $value['group'];
            }
            Yii::$app->cache->set(self::CACHE_GROUP_INFO_KEY,$activity_group_list,self::CACHE_GROUP_INFO_DURATION);
            return $activity_group_list;
        }catch (\Exception $e){
            return $custom_user_group;
        }
    }

    public function activityGroupDetail(){
        $group_res = [];
        try{
            $group = Yii::$app->RQ->AR(new ActivityGpubsGroupAR())
                ->all([
                    'select' => ['id','group_number','consignee','mobile','full_address','status','activity_gpubs_product_id','custom_user_id'],
                    'where'  =>[
                        'id'=>$this->group_id,
                    ]
                ]);
            if(!empty($group)){
                foreach ($group as $item){
                    $product         = new GpubsProduct(['id'=>$item['activity_gpubs_product_id']]);
                    $custom_user     = new CustomUser(['id'=>$item['custom_user_id']]);
                    $group_res['gpubs_id']           = $item['id'];
                    $group_res['group_number']       = $item['group_number'];
                    $group_res['gpubs_product_id']   = $product->product_id;
                    $group_res['group_consignee']    = $item['consignee'];
                    $group_res['mobile']             = $item['mobile'];
                    $group_res['img']                = $custom_user->headerImg;
                    $group_res['address']            = $item['full_address'];
                }
            }
            return $group_res;
        }catch (\Exception $e){
            return $group_res;
        }
    }


    public function getTrade(){
        $result =  ActivityGpubsGroupTicketAR::find()->
        select(['id'])->
        where([
            'custom_user_id' => Yii::$app->user->id,
            'id' => $this->trade_id,
            'is_join' => $this->is_join,
        ])->
        asArray();
        return [
            'product_id' => $result['activity_gpubs_group_id'],
            ];
    }

}
