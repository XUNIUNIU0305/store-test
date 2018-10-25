<?php
namespace custom\components;

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

    public function provideOrders(int $status = null, $currentPage, $pageSize){
        if(!$currentPage = (int)$currentPage)$currentPage = 1;
        if(!$pageSize = (int)$pageSize)$pageSize = 1;
        if(!is_null($status) && !in_array($status, Order::getStatuses()))return false;
        return new ActiveDataProvider([
            'query' => OrderAR::find()->select(['id'])->where([
                'custom_user_id' => $this->userId,
            ])->andWhere(is_null($status) ? [] : ['status' => $status])->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);
    }

    public function getQuantity(int $orderStatus = null){
        if(is_null($orderStatus)){
            $quantity = Yii::$app->RQ->AR(new OrderAR)->all([
                'select' => ['status', 'quantity' => 'COUNT(*)'],
                'where' => ['custom_user_id' => $this->userId],
                'groupBy' => ['status'],
            ]);
            return array_column($quantity, 'quantity', 'status');
        }else{
            if(!in_array($orderStatus, Order::getStatuses()))return false;
            return Yii::$app->RQ->AR(new OrderAR)->count([
                'select' => ['id'],
                'where' => [
                    'custom_user_id' => $this->userId,
                    'status' => $orderStatus,
                ],
            ]);
        }
    }
}
