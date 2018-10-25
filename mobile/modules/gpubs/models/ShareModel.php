<?php
namespace mobile\modules\gpubs\models;

use Yii;
use common\models\Model;
use common\models\parts\gpubs\GpubsGroup;
use common\components\handler\Handler;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\ActivityGpubsProductAR;
use common\ActiveRecord\ActivityGpubsProductSkuAR;
use common\ActiveRecord\ActivityGpubsGroupDetailAR;
use common\models\parts\Product;
use common\ActiveRecord\ActivityGpubsGroupTicketAR;

class ShareModel extends Model{

    const SCE_GET_INFO = 'get_info';
    const SCE_GET_DETAIL = 'get_detail';
    const SCE_GET_WX_INFO = 'get_wx_info';

    public $group_id;
    public $ticket_id;
    public $product_id;

    public function scenarios(){
        return [
            self::SCE_GET_INFO => [
                'group_id',
            ],
            self::SCE_GET_DETAIL => [
                'group_id',
                'ticket_id',
            ],
            self::SCE_GET_WX_INFO => [
                'product_id',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['group_id','product_id'],
                'required',
                'message' => 9001,
            ],
            [
                ['ticket_id'],
                'exist',
                'targetClass' => 'common\ActiveRecord\ActivityGpubsGroupTicketAR',
                'targetAttribute' => ['ticket_id' => 'id'],
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
                ['product_id'],
                'exist',
                'targetClass' => 'common\ActiveRecord\ActivityGpubsProductAR',
                'message' => 9002,
            ],
        ];
    }

    //获取团员拼购信息
    public function getInfo(){
        $group = new GpubsGroup([
            'id' => $this->group_id,
        ]);
        return  Handler::getMultiAttributes($group, [
            'group_id' => 'id',
            'owner_account' => 'custom_user_id',
            'group_number' => 'groupNumber',
            'product' => 'activity_gpubs_product_id',
            'member' => 'id',
            'status',
            'group_start_datetime',
            'gpubs_type',
            'gpubs_rule_type',
            'target_quantity',
            'present_quantity',
            'target_member',
            'present_member',
            'min_quanlity_per_member_of_group' => 'min_quantity_per_member_of_group',
            '_func' => [
                'custom_user_id' => function($id){
                    return CustomUserAR::findOne($id)->account;
                },
                'activity_gpubs_product_id' => function($id){
                    $productId = ActivityGpubsProductAR::findOne($id)->product_id;
                    $product = new Product([
                        'id' => $productId,
                    ]);
                    return [
                        'id' => $product->id,
                        'title' => $product->title,
                    ];
                },
                'id' => function($id){
                    $user =  ActivityGpubsGroupDetailAR::find()->
                                select([
                                    'custom_user_id',
                                    'custom_user_account',
                                    'join_datetime',
                                    'quantity',
                                    'product_id',
                                    'sku_attributes',
                                    'is_owner',
                                ])->where([
                                    'activity_gpubs_group_id' => $id,
                                ])->
                                orderBy([
                                    'id' => SORT_ASC,
                                ])->
                                asArray()->
                                all();
                    return array_map(function ($array) {
                        $res['custom_user_account'] = $array['custom_user_account'];
                        $res['join_datetime'] = $array['join_datetime'];
                        $res['quantity'] = $array['quantity'];
                        $res['header_img'] = CustomUserAR::findOne($array['custom_user_id'])->header_img;
                        $res['is_owner'] = $array['is_owner'];
                        $res['sku_attribute'] = unserialize($array['sku_attributes']);
                        return $res;
                    }, $user);

                },
            ],
        ]);
    }

    //获取拼购详情
    public function getDetail(){
        $group = new GpubsGroup([
            'id' => $this->group_id,
        ]);
        $status = 0; //判断当前用户是否参加过此团 0未参团 1已参团 2参团失败
        $data = ActivityGpubsGroupDetailAR::find()->select(['id'])->where([
            'activity_gpubs_group_id' => $this->group_id,
            'custom_user_id'=>Yii::$app->user->id
        ])->asArray()->one();
        if (!empty($data)){
            $status = 1;
        }else{
            if(!empty($this->ticket_id)){
                $tick_id =  ActivityGpubsGroupTicketAR::find()->
                select(['id'])->
                where([
                    'custom_user_id' => Yii::$app->user->id,
                    'id' => $this->ticket_id,
                ])->asArray()->one();
                if (!empty($tick_id)){
                    $status = 2;
                }else{
                    $this->addError('getDetail',9002);
                    return false;
                }
            }
        }
        $result =  Handler::getMultiAttributes($group, [
            'group_id' => 'id',
            'owner_account' => 'custom_user_id',
            'group_number' => 'groupNumber',
            'product' => 'activity_gpubs_product_id',
            'member' => 'id',
            'left_unixtime' => 'group_end_unixtime',
            'full_address',
            'consignee',
            'mobile',
            'status',
            'gpubs_type',
            'gpubs_rule_type',
            'target_quantity',
            'present_quantity',
            'target_member',
            'present_member',
            'spot_name',
            'min_quanlity_per_member_of_group' => 'min_quantity_per_member_of_group',//每团每人购买的最小数量
            '_func' => [
                'custom_user_id' => function($id){
                    $user = CustomUserAR::findOne($id);
                    return array(
                        'account'=>$user->account,
                        'header_img'=>$user->header_img
                    );
                },
                'activity_gpubs_product_id' => function($id){
                    $activityProduct = ActivityGpubsProductAR::findOne($id);
                    $productId = $activityProduct->product_id;
                    $product = new Product([
                        'id' => $productId,
                    ]);
                    return [
                        'id' => $product->id,
                        'title' => $product->title,
                        'image' => $product->mainImage->path,
                        'brand_name' => $product->supplierObj->brandName,
                        'original_price' => $product->price,
                        'stockCount' => ActivityGpubsProductSkuAR::find()->where(['product_id' => $productId])->sum('stock'),
                        'left_unixtime'=> $activityProduct->activity_end_unixtime - Yii::$app->time->unixTime,
                        'activity_price' => [
                            'min' => Yii::$app->RQ->AR(new ActivityGpubsProductSkuAR)->min([
                                'where' => [
                                    'product_id' => $productId,
                                ],
                            ], 'price'),
                            'max' => Yii::$app->RQ->AR(new ActivityGpubsProductSkuAR)->max([
                                'where' => [
                                    'product_id' => $productId,
                                ],
                            ], 'price'),
                        ],
                        'description' => $activityProduct->description,
                    ];
                },
                'group_end_unixtime' => function($endTime){
                    return $endTime - Yii::$app->time->unixTime;
                },
                'id' => function($id){
                    $user =  ActivityGpubsGroupDetailAR::find()->
                    select([
                        'custom_user_id',
                        'is_owner',
                    ])->where([
                        'activity_gpubs_group_id' => $id,
                    ])->
                    orderBy([
                        'id' => SORT_ASC,
                    ])->
                    asArray()->
                    all();
                    return array_map(function ($array) {
                        $res['header_img'] = CustomUserAR::findOne($array['custom_user_id'])->header_img;
                        $res['is_owner'] = $array['is_owner'];
                        return $res;
                    }, $user);

                },
            ],
        ]);
        $result['is_participate'] = $status;
        if ($result['left_unixtime'] > $result['product']['left_unixtime']){
            $result['left_unixtime'] = $result['product']['left_unixtime'];
        }
        unset($result['product']['left_unixtime']);
        return $result;
    }

    //获取微信分享信息
    public function getWxInfo(){
        $product = ActivityGpubsProductAR::find()->where(['product_id' => $this->product_id])->asArray()->one();
        return [
            'share_title'=>$product['share_title'],
            'share_subtitle'=>$product['share_subtitle'],
            'filename' => Yii::$app->params['OSS_PostHost'] . '/' . $product['filename'],
        ];
    }
}
