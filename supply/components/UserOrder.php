<?php
namespace supply\components;

use Yii;
use yii\base\Object;
use yii\base\InvalidCallException;
use common\models\parts\Order;
use yii\data\ActiveDataProvider;
use common\ActiveRecord\OrderAR;

class UserOrder extends Object{

    protected $userId;

    public function init(){
        if(Yii::$app->user->isGuest)throw new InvalidCallException;
        $this->userId = Yii::$app->user->id;
    }

    public function provideOrders(int $status = null, $currentPage, $pageSize,$searchData = null){
        if(!$currentPage = (int)$currentPage)$currentPage = 1;
        if(!$pageSize = (int)$pageSize)$pageSize = 1;
        if(!is_null($status) && !in_array($status, Order::getStatuses()))return false;
        return new ActiveDataProvider([
            'query' => OrderAR::find()->select(['id'])->where([
                'supply_user_id' => $this->userId,
            ])->andWhere(is_null($status) ? [] : ['status' => $status])->andWhere(is_null($searchData) ? [] : $searchData)->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'pay_unixtime' => SORT_ASC,
                ],
            ],
        ]);
    }

    public function getQuantity(int $status = null,$customization = Order::CUSTOM_STATUS_NO){
        if(is_null($status)){
            $quantity = Yii::$app->RQ->AR(new OrderAR)->all([
                'select' => ['status', 'quantity' => 'COUNT(*)'],
                'where' => ['supply_user_id' => $this->userId,'is_customization' => $customization],
                'groupBy' => ['status'],
            ]);
            return array_column($quantity, 'quantity', 'status');
        }else{
            if(!in_array($status, Order::getStatuses()))return false;
            return Yii::$app->RQ->AR(new OrderAR)->count([
                'select' => ['id'],
                'where' => [
                    'supply_user_id' => $this->userId,
                    'status' => $status,
                    'is_customization' => $customization,//普通订单
                ],
            ]);
        }
    }
}
