<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/31 0031
 * Time: 17:06
 */

namespace mobile\modules\customization\models;


use common\ActiveRecord\CarAlphabetAR;
use common\ActiveRecord\CarBrandAR;
use common\ActiveRecord\CarTypeAR;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\OrderCustomizationAR;
use common\ActiveRecord\OSSUploadFileAR;
use common\components\handler\Handler;
use common\models\Model;
use common\validators\order\NoValidator;
use custom\modules\account\models\OrderModel as BaseOrderModel;
use common\models\parts\Order;
use common\models\parts\OrderCustomization;
use mobile\components\validators\ImageValidator;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;

class OrderModel extends Model
{
    const SCE_SEARCH = 'search';
    const SCE_CANCEL = 'cancel';
    const SCE_VIEW = 'view';
    const SCE_BRAND = 'brand';
    const SCE_BRAND_TYPE = 'brand_type';
    const SCE_UPLOAD = 'upload';

    public $status;

    public $page = 1;

    public $page_size = 10;

    public $order_number;

    public $id;

    public $brand_id;
    public $type_id;
    public $note = '';
    public $images;

    public function scenarios()
    {
        return [
            self::SCE_SEARCH => [
                'status',
                'page',
                'page_size'
            ],
            self::SCE_CANCEL => [
                'order_number'
            ],
            self::SCE_VIEW => [
                'order_number'
            ],
            self::SCE_BRAND => [],
            self::SCE_BRAND_TYPE => [
                'id'
            ],
            self::SCE_UPLOAD => [
                'brand_id',
                'type_id',
                'note',
                'images',
                'order_number'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['status', 'order_number', 'id', 'brand_id', 'type_id', 'images'],
                'required',
                'message' => 9001
            ],
            [
                ['order_number'],
                NoValidator::class,
                'customerId' => \Yii::$app->user->id,
                'message' => 10070
            ],
            [
                ['status'],
                'in',
                'range' => array_keys(OrderCustomization::$status),
                'message' => 9002
            ],
            [
                ['page', 'page_size', 'brand_id', 'type_id'],
                'integer',
                'message' => 9002
            ],
            [
                ['id'],
                'integer',
                'message' => 9002
            ],
            [
                ['note'],
                'string',
                'message' => 9002
            ],
            [
                ['images'],
                ImageValidator::class
            ]
        ];
    }

    /**
     * 订单列表
     * @return array
     */
    public function search()
    {
        $this->status = explode(',', $this->status);
        $query = OrderAR::find()->alias('a')
            ->select(['a.id'])
            ->leftJoin(OrderCustomizationAR::tableName() . ' b', 'b.order_number = a.order_number')
            ->where([
                'is_customization'  => Order::CUSTOM_STATUS_IS,
                'b.status'          => $this->status ?? OrderCustomization::STATUS_DEFAULT,
                'custom_user_id'    => \Yii::$app->getUser()->getId()
            ])
            ->orderBy(['id' => SORT_DESC])
            ->asArray();

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->page_size,
                'page'     => $this->page - 1
            ]
        ]);

        return [
            'items' => array_map(function($id){
                return Handler::getMultiAttributes(new Order(['id' => $id]), [
                    'order_no' => 'orderNo',
                    'customization',
                    'pay_time' => 'payTime',
                    'product' => 'items',
                    'brand_name' => 'supplier',
                    '_func' => [
                        'items' => function($items){
                            return Handler::getMultiAttributes(current($items), [
                                'title',
                                'attributes' => 'SKUAttributes',
                                'total_fee' => 'totalFee',
                                'image',
                                'comments',
                                '_func' => [
                                    'image' => function ($image) {
                                        return $image->path;
                                    },
                                ],
                            ]);
                        },
                        'customization' => function($item){
                            return $item->status;
                        },
                        'supplier' => function($user){
                            return $user->getBrandName();
                        }
                    ]
                ]);
            }, $provider->models),
            'page' => $this->page,
            'page_size' => $this->page_size,
            'total_count' => $provider->totalCount
        ];
    }

    /**
     * 取消订单
     * @return bool
     */
    public function cancel()
    {
        $model = new BaseOrderModel([
            'orders_no' => [$this->order_number],
            'scenario' => BaseOrderModel::SCE_CANCEL_ORDERS
        ]);
        if($res = $model->process()){
            return true;
        } else {
            $this->addError('cancel_order', $model->getErrorCode());
            return false;
        }
    }

    /**
     * 订单详情
     * @return array|bool
     */
    public function view()
    {
        try{
            $order = new Order(['orderNumber' => $this->order_number]);
            $customization = $order->getCustomization();
            $host = \Yii::$app->params['OSS_PostHost'];
            return [
                'no' => $this->order_number,
                'pay_time' => $order->getPayTime(),
                'status' => $customization->getStatus(),
                'product' => Handler::getMultiAttributes(current($order->getItems()), [
                    'title',
                    'attributes' => 'SKUAttributes',
                    'total_fee' => 'totalFee',
                    'image',
                    'comments',
                    '_func' => [
                        'image' => function ($image) {
                            return $image->path;
                        },
                    ],
                ]),
                'notes' => $customization->getNotes(),
                'pics' => $customization->getPics(),
                'brand' => $customization->getCarBrandName(),
                'brand_id' => $customization->getCarBrandId(),
                'type_id' => $customization->getCarTypeId(),
                'type' => $customization->getCarTypeName(),
                'express_name' => $order->getExpressCorp(true),
                'express_number' => $order->getExpressNo(),
                'host' => $host
            ];
        } catch (\Exception $e){
            $this->addError('view', 11020);
            return false;
        }
    }

    /**
     * 获取品牌列表
     * @return array|false
     */
    public function brand()
    {
        try{
            $brands = CarBrandAR::find()->all();
            $alphabets = array_column($brands, 'alphabet_id');
            $alphabets = CarAlphabetAR::find()->where(['id' => $alphabets])
                ->indexBy('id')->asArray()->all();
            $res = [];
            $hostname = \Yii::$app->params['OSS_PostHost'];
            foreach ($brands as $brand){
                if(isset($alphabets[$brand->alphabet_id])){
                    $key = $alphabets[$brand->alphabet_id]['name'];
                    $res[$key][] = [
                        'id' => $brand->id,
                        'name' => $brand->name,
                        'url' => $hostname . $brand->logo_img
                    ];
                }
            }
            return $res;
        } catch (\Exception $e){
            return false;
        }
    }

    /**
     * 根据品牌获取型号
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public function brandType()
    {
        try{
            $types = CarTypeAR::find()->where(['brand_id' => $this->id])
                ->select(['id', 'name'])->asArray()->all();
            $brand = CarBrandAR::findOne($this->id);
            $brand = [
                'name' => $brand->name,
                'url' => \Yii::$app->params['OSS_PostHost'] . $brand->logo_img
            ];
            return compact('brand', 'types');
        } catch (\Exception $e){
            return false;
        }
    }

    /**
     * 定制订单上传
     * @return bool
     */
    public function upload()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $this->validateByQuery();
            $custom = new OrderCustomization([
                'order_number' => $this->order_number
            ]);
            $custom->upload($this->brand_id, $this->type_id);
            $custom->sendNote([
                'text' => $this->note,
                'type' => OrderCustomization::NOTE_TYPE_DEFAULT,
                'user_id' => \Yii::$app->user->id
            ]);
            $custom->updateImages($this->images, \Yii::$app->user->id);
            $transaction->commit();
            return true;
        } catch (\Exception $e){
            $transaction->rollBack();
            $this->addError('upload', 11021);
            return false;
        }
    }

    /**
     * @throws BadRequestHttpException
     */
    private function validateByQuery()
    {
        //验证品牌与型号
        $type = CarTypeAR::find()->select(['brand_id'])->where(['id' => $this->type_id])->scalar();
        if(!$type == $this->brand_id)
            throw new BadRequestHttpException();
        //验证图片
        $num = OSSUploadFileAR::find()->where(['filename' => $this->images])->count();
        if(intval($num) !== count($this->images))
            throw new BadRequestHttpException();
    }
}