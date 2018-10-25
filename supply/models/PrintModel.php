<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/7/5
 * Time: 下午2:17
 */

namespace supply\models;


use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\Order;
use Yii;

class PrintModel extends Model
{

    const SCE_PRINT_ORDER = 'print_order';

    public $order_id;

    public function scenarios()
    {
        return [
            self::SCE_PRINT_ORDER=>[
                'order_id',
            ],
        ];
    }

    public function rules()
    {
        return [
            [
                ['order_id'],
                'integer',
                'min' => 1000000000,
                'max' => 9999999999,
                'tooSmall' => 9002,
                'tooBig' => 9002,
                'message' => 9002,
            ],
            [
                ['order_id'],
                'common\validators\order\NoValidator',
                'supplierId' => Yii::$app->user->id,
                'message' => 1091,
            ],
        ];
    }

    /**
     *====================================================
     * 获取 打印订单数据
     * @return array
     * @author shuang.li
     * @date 2017年6月7日
     *====================================================
     */
    public function printOrder(){
        $orderModel = new Order([ 'orderNumber' => $this->order_id]);
        $emptyFunc = function($data){
            return empty($data) ? '' :$data;
        };
        return Handler::getMultiAttributes($orderModel, [
            'order_no' => 'orderNo',
            'customer_account' => 'customerAccount',
            'total_fee' => 'totalFee',
            'status',
            'express_corporation' => 'expressCorpName',
            'express_number' => 'expressNo',
            'create_time' => 'createTime',
            'pay_time' => 'payTime',
            'consignee',
            'address',
            'mobile',
            'postal_code' => 'postalCode',
            'items',
            '_func' => [
                'expressCorpName' => $emptyFunc,
                'expressNo' => $emptyFunc,
                'createTime' => $emptyFunc,
                'payTime' => $emptyFunc,
                'items' => function ($items)
                {
                    return array_map(function ($item)
                    {
                        return Handler::getMultiAttributes($item, [
                            'title',
                            'attributes' => 'SKUAttributes',
                            'price',
                            'count',
                            'total_fee' => 'totalFee',
                            'custom_id' => 'customId',
                            'bar_code' => 'barCode',
                            'image',
                            'comments',
                            '_func' => [
                                'image' => function ($image)
                                {
                                    return $image->path;
                                },

                            ],
                        ]);
                    }, $items);
                },
            ],
        ]);
    }
}