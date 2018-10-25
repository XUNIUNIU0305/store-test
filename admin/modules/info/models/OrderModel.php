<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/4/7
 * Time: 上午11:19
 */

namespace admin\modules\info\models;


use admin\components\handler\info\OrderHandler;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\custom\CustomUser;
use common\models\parts\Order;

class OrderModel extends  Model
{
    const SCE_ORDER_LIST = 'get_list';

    public $page_size;
    public $current_page;
    public $search_status;
    public $search;


    public function rules()
    {
        return [
            [
                ['current_page'],
                'default',
                'value'=>1,
            ],
            [
                ['page_size'],
                'default',
                'value'=>10,
            ],
            [
                ['search_status'],
                'in',
                'range'=>Order::getStatuses(),
                'message'=>5190,
            ],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCE_ORDER_LIST => [
                'search_status',
                'search',
                'current_page',
                'page_size',
            ],
        ];
    }


    public function getList(){
        $searchData = [];

        if (!empty($this->search_status) )
        {
            $searchData = ['and',['status'=>$this->search_status]];
        }else{
            $searchData = ['and',['>','status',0]];
        }
        //提交数据 必须在搜索条件里面才能搜索
        if (!empty($this->search) && current($this->search) && in_array(key($this->search), [
                'order_number',//订单号
                'account',//用户名
                'time',//时间
                'mobile',//手机号
            ]))
        {
            $searchKey = key($this->search);
            $searchVal = current($this->search);

            switch ($searchKey)
            {
                //订单号查询
            case 'order_number':
                $search = ['order_number'=>$searchVal];
                break;
                //手机号查询
            case 'mobile':
                $search = ['receive_mobile'=>$searchVal];
                break;
                //用户账号查询
            case 'account':
                try{
                    //获取一个customer对象
                    $customUser = new CustomUser([
                        'account' => $searchVal,
                    ]);
                    $search = ['custom_user_id'=>$customUser->id];
                }catch (\Exception $e){
                    $search = ['custom_user_id'=>0];
                }
                break;
                //时间查询
            case 'time':
                list($startTime, $endTime) = $searchVal;
                $startTime = strtotime($startTime . ' 00:00:00');
                $endTime = strtotime($endTime . ' 23:59:59');
                switch ($this->search_status)
                {
                    //未处理 付款时间
                case 1:
                    $search = ['between','pay_unixtime',$startTime, $endTime];
                    break;
                    //已处理 发货时间
                case 2:
                    $search = ['between','deliver_unixtime',$startTime, $endTime];
                    break;
                    //确认收货  收货时间
                case 3:
                    $search = ['between','receive_unixtime',$startTime, $endTime];
                    break;
                    //取消订单时间
                case 4:
                    $search = ['between','cancel_unixtime',$startTime, $endTime];
                    break;
                    //订单创建时间
                default:
                    $search = ['between','create_unixtime',$startTime, $endTime];
                    break;
                }
                break;
            }
            array_push($searchData,$search);
        }

        $data = OrderHandler::provideOrders($this->current_page, $this->page_size,$searchData);

        $orders = array_map(function($order){
            return new Order(['id' => $order['id']]);
        }, $data->models);
        $emptyFunc = function($data){
            return empty($data) ? '' : $data;
        };
        return [
            'orders' => array_map(function ($order) use ($emptyFunc)
            {
                return Handler::getMultiAttributes($order, [
                    'order_no' => 'orderNo',
                    'customer_account' => 'customerAccount',
                    'total_fee' => 'totalFee',
                    'status',
                    'express_corporation' => 'expressCorpName',
                    'express_number' => 'expressNo',
                    'create_time' => 'createTime',
                    'pay_time' => 'payTime',
                    'deliver_time' => 'deliverTime',
                    'receive_time' => 'receiveTime',
                    'close_time' => 'closeTime',
                    'cancel_time' => 'cancelTime',
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
                        'deliverTime' => $emptyFunc,
                        'receiveTime' => $emptyFunc,
                        'closeTime' => $emptyFunc,
                        'cancelTime' => $emptyFunc,
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
            }, $orders),
            'count' => $data->count,
            'total_count' => $data->totalCount,
        ];
    }



}