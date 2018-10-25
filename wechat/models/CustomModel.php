<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/13
 * Time: 18:12
 */

namespace wechat\models;

use common\ActiveRecord\OrderAR;
use common\ActiveRecord\OrderCustomizationAR;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\Order;
use common\models\parts\OrderCustomization;
use yii\data\ActiveDataProvider;

class CustomModel extends Model
{
    const SCE_GET_LIST = 'get_list';

    public $search;

    public function scenarios()
    {
        return [
            self::SCE_GET_LIST => [
                'search'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['search'],
                'default',
                'value' => []
            ]
        ];
    }

    public function getList()
    {
        $status = $this->search['status'] ?? OrderCustomization::STATUS_DEFAULT;
        $pageSize = $this->search['page_size'] ?? 10;
        $page = $this->search['page'] ?? 0;
        if(!key_exists($status, OrderCustomization::$status)){
            $this->addError('status', 9001);
        }
        $query = OrderAR::find()->select(['a.id'])->alias('a')
            ->where(['supply_user_id' => \Yii::$app->getUser()->getId()])
            ->andWhere(['b.status' => $status])
            ->orderBy(['pay_datetime'=>SORT_DESC])
            ->leftJoin(OrderCustomizationAR::tableName() . ' b', 'a.order_number = b.order_number');

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
                'page'  => $page
            ]
        ]);

        return array_map(function($order){
            return Handler::getMultiAttributes(new Order(['id' => $order->id]),[
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
                'postal_code' => 'postalCode'
            ]);
        }, $provider->models);
    }
}