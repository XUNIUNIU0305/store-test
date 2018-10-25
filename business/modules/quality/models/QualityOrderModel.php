<?php
namespace business\modules\quality\models;

use business\models\parts\Role;
use common\ActiveRecord\BusinessAreaTechnicanAR;
use common\ActiveRecord\BusinessUserAR;
use common\ActiveRecord\CarBrandAR;
use common\ActiveRecord\CarTypeAR;
use common\ActiveRecord\DistrictCityAR;
use common\ActiveRecord\QualityOrderAR;
use common\components\handler\Handler;
use common\components\handler\quality\QualityOrderHandler;
use common\models\Model;
use common\models\parts\quality\BusinessAreaTechnican;
use common\models\parts\quality\QualityOrder;
use common\models\parts\quality\Technican;
use Yii;

class QualityOrderModel extends Model
{


    const SCE_CREATE_ORDER = "create_order";//创建质保单
    const SCE_GET_ORDER_LIST = "search";//订单搜索
    const SCE_GET_ORDER_INFO = "get_order_info";//获取订单详情
    const SCE_GET_TECHNICAN = "get_technican_list";//获取当前用户技术列表


    public $username;//车主姓名

    public $area_code;//区号
    public $telephone;//电话号码

    public $mobile;//手机号码
    public $email;//邮箱
    public $address;//车主地址


    public $goods;//产品信息

    public $car_code; //车牌号
    public $car_frame;//车架号
    public $car_brand;//车品牌
    public $car_type;//车型号
    public $car_price;//车价格

    public $construct_unit;
    public $start_time;//施工日期
    public $price;//产品总价
    public $finished_time;//完成日期

    public $type;// 搜索类型
    public $keyword;//搜索关键字
    public $end_time;//结束时间
    public $page_size;
    public $current_page;
    public $id;

    //设置场景
    public function scenarios()
    {

        return [
            //创建质保订单
            self::SCE_CREATE_ORDER => [
                'area_code',
                'username',
                'telephone',
                'mobile',
                'email',
                'address',
                'car_code',
                'car_frame',
                'car_brand',
                'car_type',
                'car_price',
                'start_time',
                'finished_time',
                'price',
                'goods',
                'construct_unit',
            ],
            //搜索
            self::SCE_GET_ORDER_LIST => [
                'type',
                'start_time',
                'end_time',
                'keyword',
                'page_size',
                'current_page'
            ],

            //获取订单详情
            self::SCE_GET_ORDER_INFO => ['id'],
            //获取当前用户技师列表
            self::SCE_GET_TECHNICAN => [],
        ];
    }

    //配置规则
    public function rules()
    {

        return [
            [
                [
                    'username',
                    'mobile',
                    'address',
                    'car_code',
                    'car_frame',
                    'car_brand',
                    'car_type',
                    'car_price',
                    'start_time',
                    'finished_time',
                    'price',
                    'goods',
                    'construct_unit',
                ],
                'required',
                'message' => 9001,
                'on' => [self::SCE_CREATE_ORDER],
            ],
            [
                ['area_code'],
                'exist',
                'targetClass' => DistrictCityAR::className(),
                'targetAttribute' => ['area_code' => 'citycode'],
                'message' => 1121,
            ],
            [
                ['id'],
                'required',
                'message' => 9001,
            ],
            [
                ['id'],
                'exist',
                'targetClass' => QualityOrderAR::className(),
                'targetAttribute' => [
                    'id' => 'id',
                ],
                'message' => 7086,
            ],
            [
                ['type'],
                'in',
                'range' => [
                    0,
                    1,
                    2,
                    3,
                    4,
                    5,
                    6
                ],
                'message' => 7084,
            ],
            [
                ['type'],
                'default',
                'value' => 0,
            ],
            [
                ['keyword'],
                'default',
                'value' => '',
            ],
            [
                ['keyword'],
                'string',
                'length' => [
                    0,
                    60
                ],
                'tooLong' => 7085,
                'tooShort' => 7085,
                'message' => 7085,
            ],


            [
                ['page_size'],
                'default',
                'value' => 10,
            ],
            [
                ['current_page'],
                'default',
                'value' => 1,
            ],
            [
                ['goods'],
                'common\validators\quality\QualityOrderGoodsValidator',
                'message'=>7082,
                'messageRoundNum' => 13320,
                'messageSales' => 13321,
            ],
            [
                [
                    'finished_time',
                    'start_time',
                    'end_time'
                ],
                'date',
                'format' => 'php:Y-m-d',
                'message' => 7079,
            ],
            [
                ['car_code'],
                'string',
                'length' => [
                    7,
                    10
                ],
                'tooLong' => 7076,
                'tooShort' => 7076,
                'message' => 7076,
            ],
            [
                ['car_frame'],
                'string',
                'length' => [
                    17,
                    25
                ],
                'tooLong' => 7077,
                'tooShort' => 7077,
                'message' => 7077,
            ],
            [
                ['username'],
                'string',
                'length' => [
                    2,
                    8
                ],
                'tooLong' => 7071,
                'tooShort' => 7071,
                'message' => 7071,
            ],
            [
                ['telephone'],
                'string',
                'length' => [
                    7,
                    13
                ],
                'message' => 7072,
                'tooLong' => 7072,
                'tooShort' => 7072,
            ],
            [
                ['mobile'],
                'integer',
                'min' => 10000000000,
                'max' => 19999999999,
                'tooSmall' => 3166,
                'tooBig' => 3166,
                'message' => 3166,
            ],
            [
                ['email'],
                'email',
                'message' => 5169,
            ],
            [
                ['address'],
                'string',
                'length' => [
                    1,
                    255
                ],
                'tooLong' => 7073,
                'tooShort' => 7073,
                'message' => 7073,
            ],
            [
                ['car_brand'],
                'exist',
                'targetClass' => CarBrandAR::className(),
                'targetAttribute' => ['car_brand' => 'id'],
                'message' => 7041,
            ],
            [
                ['car_type'],
                'exist',
                'targetClass' => CarTypeAR::className(),
                'targetAttribute' => ['car_type' => 'id'],
                'message' => 7074
            ],
            [
                ['car_price'],
                'string',
                'length' => [
                    2,
                    60
                ],
                'tooLong' => 7081,
                'tooShort' => 7081,
                'message' => 7081,
            ],
            [
                ['price'],
                'number',
                'min' => 0,
                'max' => 99999999,
                'tooSmall' => 1101,
                'tooBig' => 1101,
                'message' => 13322,
            ]

        ];

    }


    /**
     * Author:JiangYi
     * Date:2017/5/28
     * Desc:技师列表
     * @return array
     */
    public function getTechnicanList()
    {
        $model = Yii::$app->BusinessUser->account->area->getTechnical(1000, 1);
        $data = array_map(function ($item)
        {
            $item = new BusinessAreaTechnican(['id' => $item['id']]);
            return [
                'id' => $item->id,
                'name' => $item->name,
                'mobile' => $item->mobile,
                'remark' => $item->remark,
            ];
        }, $model->models);
        return [
            'count' => $model->count,
            'total_count' => $model->totalCount,
            'codes' => $data,
        ];
    }

    //获取订单详情
    public function getOrderInfo()
    {

        return Handler::getMultiAttributes(new QualityOrder(['id' => $this->id]), [
            'id',
            'code',
            'owner_name' => 'ownerName',
            'owner_mobile' => 'ownerMobile',
            'owner_telephone' => 'ownerTelephone',
            'owner_email' => 'email',
            'owner_address' => 'OwnerAddress',
            'car_number' => 'carNumber',
            'car_frame' => 'carFrame',
            'car_price_range' => 'carPriceRange',
            'construct_date' => 'constructTime',
            'finished_date' => 'finishedTime',
            'brand_name' => 'Brand',
            'type_name' => 'CarType',
            'price',
            'construct_unit' => 'constructUnit',
            'goods' => 'Items',

        ]);
    }

    //查询
    public function search()
    {
        if ($this->type == 0)
        {
            $this->type = null;
        }
        try {
            $model = QualityOrderHandler::getList($this->page_size, $this->current_page, /*Yii::$app->BusinessUser->account->topArea->id*/ \business\models\parts\Area::LEVEL_UNDEFINED /*普管强制查询全局*/, $this->type, $this->keyword, $this->start_time, $this->end_time);

            $data = array_map(function ($item)
            {
                $order = new QualityOrder(['id' => $item['id']]);
                return [
                    'id' => $order->id,
                    'code' => $order->getCode(),
                    'name' => $order->getOwnerName(),
                    'mobile' => $order->getOwnerMobile(),
                    'car_code' => $order->getCarNumber(),
                    'car_frame' => $order->getCarFrame(),
                    'construct_date' => $order->getConstructTime(),
                    'finished_date' => $order->getFinishedTime(),
                ];
            }, $model->models);
            return [
                'count' => $model->count,
                'total_count' => $model->totalCount,
                'codes' => $data,
            ];

        }catch (\Exception $exception){
            return [
                'count' => 0,
                'total_count' => 0,
                'codes' => [],
            ];
        }
    }


    //创建订单信息
    public function createOrder()
    {
        if (Yii::$app->BusinessUser->account->role->id != Role::QUATERNARY){
            $this->addError('createOrder', 7100);
            return false;
        }
        if (strtotime($this->start_time) > strtotime($this->finished_time)){
            $this->addError('createOrder', 13323);
            return false;
        }

        $data = [
            'business_user_id' => $this->getBusinessId(),
            'owner_name' => $this->username,
            'owner_mobile' => $this->mobile,
            'owner_telephone' => ($this->area_code && $this->telephone ) ? $this->area_code . "-" . $this->telephone : '',
            'owner_address' => $this->address,
            'owner_email' => $this->email ? :'',
            'car_number' => $this->car_code,
            'car_frame' => $this->car_frame,
            'car_price_range' => $this->car_price,
            'car_brand_id' => $this->car_brand,
            'car_type_id' => $this->car_type,
            'construct_unit' => $this->construct_unit,
            'construct_time' => strtotime($this->start_time),
            'finished_time' => strtotime($this->finished_time),
            'price' => $this->price,
            'business_area_id' => Yii::$app->BusinessUser->account->Area->id,
            'business_top_area_id' => Yii::$app->BusinessUser->account->topArea->id,
        ];
        if (QualityOrderHandler::create($data, $this->goods, $orderCode))
        {
            return ['orderCode' => $orderCode];
        }
        $this->addError('createOrder', 7083);
        return false;

    }

    //获取客户ID
    protected function getBusinessId()
    {
        return Yii::$app->BusinessUser->account->id;
    }
}
