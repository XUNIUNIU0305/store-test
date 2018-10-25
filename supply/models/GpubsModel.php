<?php
/**
 * Created by PhpStorm.
 * User: tangzhaofeng
 * Date: 18-9-10
 * Time: 下午6:33
 */
namespace supply\models;

use common\ActiveRecord\CustomUserAR;
use common\components\handler\OSSImageHandler;
use common\models\parts\Express;
use common\models\parts\gpubs\GpubsProduct;
use common\models\parts\trade\PaymentMethodList;
use Yii;
use common\models\Model;
use yii\data\ActiveDataProvider;
use common\ActiveRecord\ActivityGpubsGroupAR;
use common\models\parts\gpubs\GpubsGroupDetail;
use common\models\parts\gpubs\GpubsGroup;
use common\components\handler\Handler;
use common\ActiveRecord\ExpressCorporationAR;
use common\models\RapidQuery;
use PHPExcel;

class GpubsModel extends Model
{
    const SCE_GET_LIST = 'get_list';
    const SCE_GET_QUANTITY = 'quantity';
    const SCE_SET_DELIVERED = 'set_delivered';



    public $status;
    public $current_page;
    public $page_size;
    public $order_id;
    public $express_corporation;
    public $express_number;
    public $group_id;


    //==================搜索条件==================

    //订单号
    public $order_no;
    //购买人
    public $account;
    //收货人、收货地址
    public  $receive_consignee,$receive_address;

    //时间
    public $create_time,$deliver_time;

    public function scenarios()
    {
        return [
            self::SCE_GET_LIST => [
                'order_no',
                'status',
                'current_page',
                'page_size',
                'receive_consignee',
                'receive_address',
                'create_time',
                'deliver_time',
                'account',
            ],
            self::SCE_GET_QUANTITY => [

            ],
            self::SCE_SET_DELIVERED => [
                'group_id',
            ],
        ];
    }

    public function rules()
    {
        return [
            [
                ['status'],
                'default',
                'value' => GpubsGroup::STATUS_ESTABLISH,
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
                [
                    'status',
                    'current_page',
                    'page_size',
                    'order_id',
                ],
                'required',
                'message' => 9001,
            ],
            [
                ['status'],
                'in',
                'range' => [
                    GpubsGroup::STATUS_ESTABLISH,
                    GpubsGroup::STATUS_DELIVERED,
                ],
                'message' => 1081,
            ],
            [
                [
                    'current_page',
                    'page_size',
                    'express_corporation'
                ],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['group_id'],
                'integer',
                'min' => 1,
                'max' => 9999999999,
                'tooSmall' => 9002,
                'tooBig' => 9002,
                'message' => 9002,
            ],
            [
                ['group_id'],
                'common\validators\gpubs\groupValidator',
                'supplierId' => Yii::$app->user->id,
                'message' => 1091,
            ],

        ];
    }

    public function setDelivered()
    {
        $order = new GpubsGroup([
            'groupNumber' => $this->group_id,
        ]);
        if ($order->setStatus(GpubsGroup::STATUS_DELIVERED))
        {
            return true;
        }
        else
        {
            $this->addError('setDelivered', 1094);
            return false;
        }
    }

    private function GpubsOrders (int $status = null, $currentPage, $pageSize,$searchData = null){
        if(!$currentPage = (int)$currentPage)$currentPage = 1;
        if(!$pageSize = (int)$pageSize)$pageSize = 1;
        if(!is_null($status) && !in_array($status, [GpubsGroup::STATUS_ESTABLISH, GpubsGroup::STATUS_DELIVERED]))return false;
        return new ActiveDataProvider([
            'query' => ActivityGpubsGroupAR::find()->select(['id'])->where([
                'supply_user_id' => Yii::$app->user->id,
            ])->andFilterWhere(['status'=>$status])->andWhere($searchData)->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'group_establish_datetime' => SORT_ASC,
                ],
            ],
        ]);
    }

    public function getList()
    {
        $searchData = $this->searchCondition();
        $status = $this->status === 0 ? null : (int)$this->status;
        $data = $this->GpubsOrders($status, $this->current_page, $this->page_size, $searchData);
        $orders = array_map(function ($order){
            return new GpubsGroup(['id' => $order['id']]);
        }, $data->models);

        return [
            'orders' => array_map(function ($order){
                return self::getOrderInfo($order);
            }, $orders),
            'count' => $data->count,
            'total_count' => $data->totalCount,
        ];
    }
    public function Quantity()
    {
        $group = Yii::$app->RQ->AR(new ActivityGpubsGroupAR)->all([
            'select' => ['status', 'quantity' => 'COUNT(*)'],
            'where' => ['supply_user_id' => Yii::$app->user->id,'gpubs_type' => GpubsProduct::GPUBS_TYPE_INVITE],
            'groupBy' => ['status'],
        ]);
        $quantity = array_column($group, 'quantity', 'status');

        return [
            'establish' => $quantity[GpubsGroup::STATUS_ESTABLISH] ?? 0,
            'delivered' => $quantity[GpubsGroup::STATUS_DELIVERED] ?? 0,
        ];
    }

    private function getOrderInfo($group){
        return Handler::getMultiAttributes($group, [
            'order_no' => 'group_number',
            'customer_account' => 'customUser',
            'total_fee' => 'total_fee',
            'status',
            'create_time' => 'group_establish_datetime',
            'deliver_time' => 'deliverTime',
            'consignee',
            'address' => 'full_address',
            'mobile',
            'postal_code' => 'postal_code',
            'quantity' => 'present_quantity',
            'items' =>'detail',
            '_func' => [
                'detail' => function ($details)
                {
                    return array_map(function ($detail)
                    {
                        return Handler::getMultiAttributes($detail, [
                            'title' => 'product_title',
                            'attributes' => 'SKUattributes',
                            'image' => 'product_image_filename',
                            'price' => 'product_sku_price',
                            'quantity',
                            'comments' => 'comment',
                            '_func' => [
                                'product_image_filename'=> function ($image)
                                {
                                    return Yii::$app->params['OSS_PostHost'] . '/' . $image;
                                }
                            ],
                        ]);
                    },$details);

                },
                'customUser' => function($user){
                    return $user->account;
                },
        ]
        ]);
    }
    private function explodeTime($time){
        if(strpos($time,',') !== false){
            list($startTime,$endTime) = explode(',',$time);
            return [strtotime($startTime.' 00:00:00'),strtotime($endTime . ' 23:59:59')];
        }
        $this->addError('implodeTime',1161);
        return false;

    }
    protected function searchCondition(){
        $searchData = ['and',['in','status',[GpubsGroup::STATUS_ESTABLISH,GpubsGroup::STATUS_DELIVERED]]];
        $searchData[] = ['and',['=','gpubs_type',GpubsProduct::GPUBS_TYPE_INVITE]];
        if(!empty($this->order_no)) {
            array_push($searchData,['=','group_number',$this->order_no]);
        }

        if(!empty($this->account)) {
            if($id = Yii::$app->RQ->AR(new CustomUserAR())->scalar([
                'select'=>['id'],
                'where'=>['account'=>$this->account]
            ])){
                array_push($searchData,['=','custom_user_id',$id]);
            }else{
                array_push($searchData,['=','custom_user_id',self::DEFAULT_USER_ID]);
            }
        }

        if(!empty($this->receive_consignee)) {
            $searchData[] = ['like','consignee',$this->consignee];
        }

        if(!empty($this->receive_address)) {
            $searchData[] = ['like','full_address',$this->receive_address];
        }

        if(!empty($this->create_time)) {
            $time = self::explodeTime($this->create_time);
            $searchData[] = ['between', 'group_establish_unixtime', $time[0], $time[1]];
        }

        if(!empty($this->deliver_time)) {
            $time = self::explodeTime($this->deliver_time);
            $searchData[] = ['between', 'group_deliver_unixtime', $time[0], $time[1]];
        }
        return $searchData;
    }


}
