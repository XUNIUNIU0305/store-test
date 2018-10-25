<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/8/3 0003
 * Time: 11:12
 */

namespace mobile\modules\membrane\models;


use common\ActiveRecord\MembraneOrderAR;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\MembraneOrder;
use yii\data\ActiveDataProvider;

class OrderModel extends Model
{
    const SCE_LIST = 'search';
    const SCE_VIEW = 'view';
    const SCE_CANCEL = 'cancel';

    public $no;

    public $status;

    public $page;

    public $page_size;

    public function scenarios()
    {
        return [
            self::SCE_LIST => [
                'status',
                'page',
                'page_size'
            ],
            self::SCE_VIEW => ['no'],
            self::SCE_CANCEL => ['no']
        ];
    }

    public function rules()
    {
        return [
            [
                ['no'],
                'required',
                'message' => 9001
            ],
            [
                ['no'],
                'integer',
                'message' => 9002
            ],
            [
                ['status'],
                'in',
                'range' => MembraneOrder::$activeStatus,
                'message' => 9002
            ]
        ];
    }

    /**
     * @return array
     * 订单列表
     */
    public function search()
    {
        $query = MembraneOrderAR::find()
            ->where(['custom_user_id' => \Yii::$app->user->id])
            ->andWhere(['status' => MembraneOrder::$activeStatus])
            ->andFilterWhere(['status' => $this->status])
            ->orderBy(['id' => SORT_DESC]);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'page' => $this->page - 1,
                'pageSize' => $this->page_size
            ]
        ]);
        $items = array_map(function($item){
            $obj = new MembraneOrder(['AR' => $item]);
            return Handler::getMultiAttributes($obj, [
                'no',
                'receiveName',
                'receiveAddress',
                'receiveMobile',
                'receiveCode',
                'remark',
                'createdDate',
                'payDate',
                'acceptDate',
                'finishDate',
                'status',
                'items',
                '_func' => [
                    'items' => function($items){
                        return array_map(function($item){
                            return Handler::getMultiAttributes($item, [
                                'id',
                                'membrane_product_id' => 'membraneProductId',
                                'price',
                                'name',
                                'remark',
                                'image',
                                'attributes',
                                '_func' => [
                                    'attributes' => function($attributes){
                                        return array_map(function($item){
                                            return Handler::getMultiAttributes($item, [
                                                'block' => 'membrane_item_block',
                                                'block_id' => 'membrane_item_block_id',
                                                'type' => 'membrane_item_type'
                                            ]);
                                        }, $attributes);
                                    }
                                ]
                            ]);
                        }, $items);
                    }
                ]
            ]);
        }, $provider->models);
        return [
            'items' => $items,
            'page' => $this->page,
            'page_size' => $this->page_size,
            'count' => $provider->totalCount
        ];
    }

    public function view()
    {
        try{
            $order = new MembraneOrder(['no' => $this->no]);
            return Handler::getMultiAttributes($order, [
                'no',
                'totalFee',
                'account',
                'payMethod',
                'receiveName',
                'receiveAddress',
                'receiveMobile',
                'receiveCode',
                'remark',
                'createdDate',
                'payDate',
                'acceptDate',
                'finishDate',
                'status',
                'items',
                '_func' => [
                    'items' => function($items){
                        return array_map(function($item){
                            return Handler::getMultiAttributes($item, [
                                'id',
                                'membrane_product_id' => 'membraneProductId',
                                'price',
                                'name',
                                'remark',
                                'image',
                                'attributes',
                                '_func' => [
                                    'attributes' => function($attributes){
                                        return array_map(function($item){
                                            return Handler::getMultiAttributes($item, [
                                                'block' => 'membrane_item_block',
                                                'block_id' => 'membrane_item_block_id',
                                                'type' => 'membrane_item_type'
                                            ]);
                                        }, $attributes);
                                    }
                                ]
                            ]);
                        }, $items);
                    }
                ]
            ]);
        } catch (\Exception $e){
            $this->addError('', 9001);
            return false;
        }
    }

    /**
     * 取消订单
     * @return bool
     */
    public function cancel()
    {
        try{
            $order = new MembraneOrder(['no' => $this->no]);
            /* start 临时限制 1225订单无法取消 */
            $orderCreateUnixtime = strtotime($order->getCreatedDate());
            if($orderCreateUnixtime >= strtotime('2017-12-25 00:00:00') &&
                $orderCreateUnixtime <= strtotime('2017-12-25 23:59:59')){
                $this->addError('cancelOrders', 3380);
                return false;
            }
            /* end 1225订单无法取消 */
            $order->customCancel();
            return true;
        } catch (\Exception $e){
            $this->addError('cancel', 3351);
            return false;
        }
    }
}
