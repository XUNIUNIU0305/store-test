<?php
namespace custom\modules\account\models;

use common\models\parts\gpubs\GpubsProduct;
use Yii;
use common\models\Model;
use common\models\parts\gpubs\GpubsGroupDetail;
use common\models\parts\gpubs\GpubsGroup;
use common\models\parts\custom\CustomUser;
use yii\data\ActiveDataProvider;
use common\ActiveRecord\ActivityGpubsGroupDetailAR;
use common\components\handler\Handler;
use common\ActiveRecord\CustomUserAR;

class GpubsPickingUpModel extends Model{

    const SCE_GET_DETAIL_LIST = 'get_detail_list';
    const SCE_GET_DETAIL_INFO = 'get_detail_info';
    const SCE_PICK_UP = 'pickUp';

    public $current_page;
    public $page_size;
    public $status;
    public $group_number;
    public $account;
    public $pick_start_date;
    public $pick_end_date;
    public $picking_up_number;
    public $quantity;
    public $product_title;

    public function scenarios(){
        return [
            self::SCE_GET_DETAIL_LIST => [
                'current_page',
                'page_size',
                'status',
                'group_number',
                'account',
                'pick_start_date',
                'pick_end_date',
                'product_title',
            ],
            self::SCE_GET_DETAIL_INFO => [
                'picking_up_number',
            ],
            self::SCE_PICK_UP => [
                'picking_up_number',
                'quantity',
            ],
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
                ['status'],
                'default',
                'value' => 0,
            ],
            [
                ['picking_up_number', 'quantity'],
                'required',
                'message' => 9001,
            ],
            [
                ['current_page', 'page_size', 'quantity'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['status'],
                'in',
                'range' => [
                    0,
                    GpubsGroupDetail::STATUS_UNPICK,
                    GpubsGroupDetail::STATUS_PICKED_PART,
                    GpubsGroupDetail::STATUS_PICKED_ALL,
                ],
                'message' => 9002,
            ],
            [
                ['group_number'],
                'exist',
                'targetClass' => 'common\ActiveRecord\ActivityGpubsGroupAR',
                'targetAttribute' => ['group_number'],
                'filter' => [
                    'status' => [
                            GpubsGroup::STATUS_ESTABLISH,
                            GpubsGroup::STATUS_DELIVERED,
                        ]
                ],
                'message' => 3411,
            ],
            [
                ['account'],
                'exist',
                'targetClass' => 'common\ActiveRecord\CustomUserAR',
                'targetAttribute' => ['account'],
                'filter' => [
                    'status' => CustomUser::STATUS_NORMAL,
                ],
                'message' => 3412,
            ],
            [
                ['pick_start_date', 'pick_end_date'],
                'date',
                'format' => 'Y-m-d',
                'message' => 9002,
            ],
            [
                ['product_title'],
                'string',
                'message' => 9002,
            ],
            [
                ['picking_up_number'],
                'integer',
                'min' => 100000,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
        ];
    }

    public function pickUp(){
        $id = Yii::$app->RQ->AR(new ActivityGpubsGroupDetailAR)->scalar([
            'select' => ['id'],
            'where' => [
                'own_user_id' => Yii::$app->user->id,
                'picking_up_number' => $this->picking_up_number,
            ],
        ]);
        if(!$id){
            $this->addError('pickUp', 3413);
            return false;
        }
        $detail = new GpubsGroupDetail([
            'id' => $id,
        ]);
        if($detail->quantity - $detail->picked_up_quantity < $this->quantity){
            $this->addError('pickUp', 3414);
            return false;
        }
        if($detail->pick($this->picking_up_number, $this->quantity, false)){
            return true;
        }else{
            $this->addError('pickUp', 3415);
            return false;
        }
    }

    public function getDetailInfo(){
        $id = Yii::$app->RQ->AR(new ActivityGpubsGroupDetailAR)->scalar([
            'select' => ['id'],
            'where' => [
                'own_user_id' => Yii::$app->user->id,
                'picking_up_number' => $this->picking_up_number,
            ],
        ]);
        if(!$id)return true;
        $detail = new GpubsGroupDetail([
            'id' => $id,
        ]);
        return Handler::getMultiAttributes($detail, [
            'product_title',
            'product_image' => 'product_image_filename',
            'sku_attributes' => 'skuAttributes',
            'quantity',
            'picked_up_quantity',
            '_func' => [
                'product_image_filename' => function($name){
                    return Yii::$app->params['OSS_PostHost'] . '/' . $name;
                },
            ],
        ]);
    }

    public function getDetailList(){
        $pickStartDate = $this->pick_start_date ? ($this->pick_start_date . ' 00:00:00') : '';
        $pickEndDate = $this->pick_end_date ? ($this->pick_end_date . ' 23:59:59') : '';
        $status = $this->status ? : [GpubsGroupDetail::STATUS_UNPICK, GpubsGroupDetail::STATUS_PICKED_PART, GpubsGroupDetail::STATUS_PICKED_ALL];
        $provider = new ActiveDataProvider([
            'query' => ActivityGpubsGroupDetailAR::find()->
                select([
                    'id',
                    'group_number',
                    'product_title',
                    'custom_user_account',
                    'quantity',
                    'picked_up_quantity',
                    'last_pick_up_datetime',
                    'status',
                    'sku_attributes',
                    'custom_user_id',
                ])->
                where([
                    'own_user_id' => Yii::$app->user->id,
                    'status' => $status,
                    'gpubs_type'=>GpubsProduct::GPUBS_TYPE_INVITE,
                ])->
                andFilterWhere([
                    'like','product_title',$this->product_title,
                ])->
                andFilterWhere([
                    'group_number' => $this->group_number,
                    'custom_user_account' => $this->account,
                ])->
                andFilterWhere([
                    '>=', 'last_pick_up_datetime', $pickStartDate,
                ])->
                andFilterWhere([
                    '<=', 'last_pick_up_datetime', $pickEndDate,
                ])->
                asArray(),
            'pagination' => [
                'page' => $this->current_page - 1,
                'pageSize' => $this->page_size,
            ],
            'sort' => [
                'defaultOrder' => [
                    'last_pick_up_unixtime' => SORT_DESC,
                ],
            ],
        ]);
        return Handler::getMultiAttributes($provider, [
            'count',
            'total_count' => 'totalCount',
            'data' => 'models',
            '_func' => [
                'models' => function($models){
                    return array_map(function($model){
                        return Handler::getMultiAttributes($model, [
                            'group_number',
                            'product_title',
                            'custom_user_account',
                            'total_quantity' => 'quantity',
                            'picked_up_quantity',
                            'last_pick_up_datetime',
                            'status',
                            'sku_attributes',
                            'custom_user_mobile' => 'custom_user_id',
                            '_func' => [
                                'last_pick_up_datetime' => function($date){
                                    if($date == '0000-01-01 00:00:00'){
                                        return '';
                                    }else{
                                        return $date;
                                    }
                                },
                                'sku_attributes' => function($date){
                                    return unserialize($date);
                                },
                                'custom_user_id' => function($id){
                                    return CustomUserAR::findOne($id)->mobile;
                                },
                            ],
                        ]);
                    }, $models);
                },
            ],
        ]);
    }
}
