<?php
namespace custom\modules\account\controllers;

use Yii;
use common\controllers\Controller;
use custom\modules\account\models\OrderModel;

class OrderController extends Controller{

    protected $access = [
        'all' => ['@', 'get'],
        'unpaid' => ['@', 'get'],
        'undeliver' => ['@', 'get'],
        'delivered' => ['@', 'get'],
        'confirmed' => ['@', 'get'],
        'canceled' => ['@', 'get'],
        'closed' => ['@', 'get'],
        'detail' => ['@', 'get'],
        'confirm' => ['@', 'post'],
        'list' => ['@', 'get'],
        'info' => ['@', 'get'],
        'account-orders' => [null, 'get'],
        'pay' => ['@', 'post'],
        'quantity' => ['@', 'get'],
        'cancel' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'pay' => OrderModel::SCE_PAY_ORDERS,
        'cancel' => OrderModel::SCE_CANCEL_ORDERS,
        '_model' => '\custom\modules\account\models\OrderModel',
    ];

    public function actionQuantity(){
        return $this->success(OrderModel::getOrderQuantity());
    }

    public function actionAll(){
        if(Yii::$app->user->isGuest){
            Yii::$app->user->loginRequired();
        }
        return $this->render('order');
    }

    public function actionUnpaid(){
        if(Yii::$app->user->isGuest){
            Yii::$app->user->loginRequired();
        }
        return $this->render('order');
    }

    public function actionUndeliver(){
        if(Yii::$app->user->isGuest){
            Yii::$app->user->loginRequired();
        }
        return $this->render('order');
    }

    public function actionDelivered(){
        if(Yii::$app->user->isGuest){
            Yii::$app->user->loginRequired();
        }
        return $this->render('order');
    }

    public function actionConfirmed(){
        if(Yii::$app->user->isGuest){
            Yii::$app->user->loginRequired();
        }
        return $this->render('order');
    }

    public function actionCanceled(){
        if(Yii::$app->user->isGuest){
            Yii::$app->user->loginRequired();
        }
        return $this->render('order');
    }

    public function actionClosed(){
        if(Yii::$app->user->isGuest){
            Yii::$app->user->loginRequired();
        }
        return $this->render('order');
    }
    
    public function actionDetail(){
        if(Yii::$app->user->isGuest){
            Yii::$app->user->loginRequired();
        }
        $this->layout = 'empty';
        return $this->render('order_detail');
    }

    public function actionConfirm(){
        $orderModel = new OrderModel([
            'scenario' => OrderModel::SCE_CONFIRM_ORDER,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($result = $orderModel->process()){
            return $this->success(['receive_time' => $result]);
        }else{
            return $this->failure($orderModel->errorCode);
        }
    }

    public function actionList(){
        $orderModel = new OrderModel([
            'scenario' => OrderModel::SCE_GET_LIST,
            'attributes' => Yii::$app->request->get(),
        ]);
        if($result = $orderModel->process()){
            return $this->success($result);
        }else{
            return $this->failure($orderModel->errorCode);
        }
    }

    public function actionInfo(){
        $orderModel = new OrderModel([
            'scenario' => OrderModel::SCE_GET_ORDER_INFO,
            'attributes' => Yii::$app->request->get(),
        ]);
        if($result = $orderModel->process()){
            return $this->success($result);
        }else{
            return $this->failure($orderModel->errorCode);
        }
    }

    //临时功能
    public function actionAccountOrders(){
        $orderModel = new OrderModel([
            'scenario' => OrderModel::SCE_GET_ACCOUNT_ORDERS,
            'attributes' => Yii::$app->request->get(),
        ]);
        if($result = $orderModel->process()){
            return $this->success($result);
        }else{
            return $this->failure($orderModel->errorCode);
        }
    }
}
