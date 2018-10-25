<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-6-22
 * Time: 下午6:01
 */

namespace admin\modules\info\models;

use admin\components\handler\GpubsHandler;
use common\ActiveRecord\ActivityGpubsGroupAR;
use common\ActiveRecord\ActivityGpubsGroupDetailAR;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\ProductAR;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\gpubs\GpubsGroup;
use common\models\parts\gpubs\GpubsGroupDetail;
use common\models\parts\gpubs\GpubsProduct;
use common\models\parts\Order;
use Yii;

class GpubsDetailModel extends Model
{
    const SCE_DETAIL_LIST = 'detail_list';

    const STATUS_CANCELED = 0;
    const STATUS_WAIT = 1;
    const STATUS_UNPICK = 2;
    const STATUS_PICKED_PART = 3;
    const STATUS_PICKED_ALL = 4;
    const STATUS_ALL = 5;
    const STATUS_UNDELIVER = 6;
    const STATUS_DELIVER = 7;
    const STATUS_CONFIRMED = 8;
    const STATUS_CLOSE = 9;

    const GPUBS_TYPE_ALL = 3;


    public $status;
    public $mobile;
    public $detail_number;
    public $start_datetime;
    public $end_datetime;
    public $current_page;
    public $page_size;
    public $gpubs_type;
    public $group_number;
    public $custom_user_account;
    public $product_title;

    private $groupDetailStatus = [

        self::STATUS_CANCELED,
        self::STATUS_WAIT,
        self::STATUS_UNPICK,
        self::STATUS_PICKED_PART,
        self::STATUS_PICKED_ALL,
        self::STATUS_ALL, // 全部
        self::STATUS_UNDELIVER, // 未发货
        self::STATUS_DELIVER, // 已发货
        self::STATUS_CONFIRMED, // 已确认
        self::STATUS_CLOSE, // 已关闭
    ];

    public function scenarios()
    {
        return [
            self::SCE_DETAIL_LIST => [
                'status',
                'detail_number',
                'mobile',
                'start_datetime',
                'end_datetime',
                'current_page',
                'page_size',
                'gpubs_type',
                'group_number',
                'custom_user_account',
                'product_title',
            ],
        ];
    }

    public function rules()
    {
        return [
            [
                ['current_page', 'page_size', 'gpubs_type'],
                'required',
                'message' => 9001,
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
                ['status'],
                'default',
                'value' => self::STATUS_ALL,
            ],
            [
                ['status'],
                'in',
                'range' => $this->groupDetailStatus,
                'message' => 5514,
            ],
            [
                ['start_datetime', 'end_datetime'],
                'date',
                'format' => 'yyyy-M-d H:m:s',
                'message' => 5504,
            ],
            [
                ['detail_number'],
                'exist',
                'targetClass' => ActivityGpubsGroupDetailAR::className(),
                'targetAttribute' => ['detail_number' => 'detail_number'],
                'message' => 5515,
            ],
            [
                ['mobile'],
                'exist',
                'targetClass' => CustomUserAR::className(),
                'targetAttribute' => ['mobile' => 'mobile'],
                'message' => 5516,
            ],
            [
                ['gpubs_type'],
                'in',
                'range' => [GpubsProduct::GPUBS_TYPE_INVITE,GpubsProduct::GPUBS_TYPE_DELIVER, self::GPUBS_TYPE_ALL], // 1-自提 2-送货 3-全部
                'message' => 5528,
            ],
            [
                ['group_number'],
                'exist',
                'targetClass' => ActivityGpubsGroupAR::className(),
                'targetAttribute' => ['group_number' => 'group_number'],
                'message' => 5532,
            ],
            [
                ['custom_user_account'],
                'exist',
                'targetClass' => CustomUserAR::className(),
                'targetAttribute' => ['custom_user_account' => 'account'],
                'message' => 5533,
            ],
            [
                ['product_title'],
                'string',
                'message' => 9002,
            ],
        ];
    }

    public function detailList()
    {
        // 做查询筛选
        $searchData = $this->searchCondition();
        $provider = GpubsHandler::provideGpubsDetailList($this->current_page, $this->page_size, $searchData);
        return Handler::getMultiAttributes($provider, [
            'count',
            'total_count' => 'totalCount',
            'list' => 'models',
            '_func' => [
                'models' => function ($models) {
                    return array_map(function($model) {
                        $gpubsDetail = new GpubsGroupDetail(['id' => $model['id']]);
                        return Handler::getMultiAttributes($gpubsDetail, [
                            'id',
                            'detailNumber',
                            'order_number',
                            'status',
                            'total_fee',
                            'join_datetime',

                            'product_id',
                            'product_sku_id',
                            'product_title',
                            'product_image_filename',
                            'comment',
                            'quantity',
                            'full_address',
                            'express_number' => 'order',

                            'picked_up_quantity',
                            'custom_user_id',
                            'skuAttributes',
                            'custom_user' => 'customUser',
                            'gpubsGroup' => 'group',
                            '_func' => [
                                'status' => function($status) use ($gpubsDetail) {
                                    if ( $gpubsDetail->gpubs_type == GpubsProduct::GPUBS_TYPE_DELIVER && $status == GpubsGroup::STATUS_ESTABLISH ) {
                                        $orderStatus = OrderAR::find()->select(['status'])->where(['order_number' => $gpubsDetail->order_number])->scalar();
                                        switch ($orderStatus) {
                                            case Order::STATUS_UNDELIVER:
                                                return self::STATUS_UNDELIVER;
                                                break;
                                            case Order::STATUS_DELIVERED:
                                                return self::STATUS_DELIVER;
                                                break;
                                            case Order::STATUS_CONFIRMED:
                                                return self::STATUS_CONFIRMED;
                                                break;
                                            case Order::STATUS_CLOSED:
                                                return self::STATUS_CLOSE;
                                                break;
                                        }
                                    }else{
                                        return $status;
                                    }
                                },
                                'group' => function($group) {
                                    return [
                                        'group_establish_datetime' => $group->group_establish_datetime,
                                        'target_quantity' => $group->target_quantity,
                                        'present_quantity' => $group->present_quantity,
                                        'picked_up_quantity' => $group->picked_up_quantity,
                                        'consignee' => $group->consignee, // 收货人/联系人
                                        'mobile' => $group->mobile,
                                        'detailed_address' => $group->detailed_address,
                                        'full_address' => $group->full_address,
                                        'status' => $group->status,
                                        'group_number' => $group->group_number,
                                        'custom_user_account' => $group->customUser->account,
                                        'gpubs_type' => $group->gpubs_type,
                                        'spot_name' => $group->spot_name,
                                    ];
                                },
                                'customUser' => function($customUser) {
                                    return [
                                        'account' => $customUser->account,
                                        'mobile' => $customUser->mobile,
                                        'shop_name' => $customUser->shopName,
                                        'email' => $customUser->email,
                                    ];
                                },
                                'product_image_filename' => function($product_image_filename) {
                                    return Yii::$app->params['OSS_PostHost'] . '/' .$product_image_filename;
                                },
                                'order' => function($order) {
                                    if($order){
                                        return (string)$order->expressCorpName . ' ' . (string)$order->expressNo;
                                    }else{
                                        return '';
                                    }
                                },
                            ],
                        ]);
                    }, $models);
                },
            ],
        ]);
    }

    protected function searchCondition()
    {
        $searchData = ['and'];
        if ($this->status != self::STATUS_ALL) {
            if ($this->status < self::STATUS_ALL) {
                array_push($searchData, 'status = '. $this->status);
            } elseif ($this->status > self::STATUS_ALL ) {
                switch ($this->status) {
                    case self::STATUS_UNDELIVER:
                        $orderStatus = Order::STATUS_UNDELIVER;
                        break;
                    case self::STATUS_DELIVER:
                        $orderStatus = Order::STATUS_DELIVERED;
                        break;
                    case self::STATUS_CONFIRMED:
                        $orderStatus = Order::STATUS_CONFIRMED;
                        break;
                    case self::STATUS_CLOSE:
                        $orderStatus = Order::STATUS_CLOSED;
                        break;
                    default:
                        break;
                }
                $detailNumbers = ActivityGpubsGroupDetailAR::find()->select(['order_number'])->column();
                if (count($detailNumbers) != 0) {
                    $orderNumbers = OrderAR::find()->select(['order_number'])->where(['status' => $orderStatus])->andWhere(['in', 'order_number', $detailNumbers])->column();
                    array_push($searchData, ['in', 'order_number', $orderNumbers]);
                }
            }
        }

        if (!empty($this->detail_number)) {
            array_push($searchData,'detail_number = ' . $this->detail_number);
        }

        if (!empty($this->group_number)) {
            array_push($searchData, 'group_number = ' . $this->group_number);
        }

        if (!empty($this->custom_user_account)) {
            $ownUserId = CustomUserAR::find()->select('id')->where(['account' => $this->custom_user_account])->scalar();
            array_push($searchData, 'own_user_id = ' . $ownUserId);
        }

        if (!empty($this->product_title)) {
            $productIds = ProductAR::find()->select(['id'])->where(['like', 'title', $this->product_title])->column();
            array_push($searchData, ['in', 'product_id', $productIds]);
        }

        if (!empty($this->mobile)) {
            if ($id = Yii::$app->RQ->AR(new CustomUserAR)->scalar([
                'select' => ['id'],
                'where' => ['mobile' => $this->mobile]
            ])) {
                array_push($searchData,'custom_user_id = '. $id);
            } else {
                array_push($searchData,'custom_user_id = 0');
            }
        }

        if (!empty($this->gpubs_type)) {
            switch ($this->gpubs_type) {
                case self::GPUBS_TYPE_ALL:
                    break;
                default:
                    array_push($searchData, 'gpubs_type = '. $this->gpubs_type);
            }
        }

        if (!empty($this->start_datetime) && !empty($this->end_datetime)) {
            // $searchData[] = ['between', 'join_datetime', $this->start_datetime, $this->end_datetime];
            $searchData[] = ['between', 'join_unixtime', strtotime($this->start_datetime), strtotime($this->end_datetime)];
        } elseif (!empty($this->start_datetime) && empty($this->end_datetime)) {
            // $searchData[] = ['>', 'join_datetime', $this->start_datetime];
            $searchData[] = ['>', 'join_unixtime', strtotime($this->start_datetime)];
        } elseif (empty($this->start_datetime) && !empty($this->end_datetime)) {
            // $searchData[] = ['<', 'join_datetime', $this->end_datetime];
            $searchData[] = ['<', 'join_unixtime', strtotime($this->end_datetime)];
        }
        return $searchData;
    }
}
