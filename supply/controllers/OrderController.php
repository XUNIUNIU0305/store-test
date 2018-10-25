<?php
namespace supply\controllers;

use common\models\parts\ExpressCorporation;
use common\models\parts\SupplyUserExpress;
use custom\modules\account\models\IndexModel;
use Yii;
use common\controllers\Controller;
use supply\models\OrderModel;
class OrderController extends Controller{

    public $layout = 'main';

    protected $access = [
        'index' => ['@', 'get'],
        'list' => ['@', 'get'],
        'export' => ['@', 'get'],
        'express' => [null, 'get'],
        'deliver' => ['@', 'post'],
        'quantity' => ['@', 'get'],
        'get-detail' => ['@', 'get'],
        'express-detail' => ['@', 'get'],
    ];


    protected $actionUsingDefaultProcess = [
        'get-detail'=>OrderModel::SCE_DETAIL,
        'list'=>OrderModel::SCE_GET_LIST,
        'export'=>OrderModel::SCE_EXPORT_ORDRE,
        'express-detail'=>OrderModel::SCE_GET_EXPRESS,
        '_model'=>'supply\models\OrderModel',
    ];

    public function actionQuantity(){
        return $this->success(OrderModel::getOrderQuantity());
    }

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionExpress(){
        return $this->success([
            'items' => ExpressCorporation::getExpressItems(),
            'common' => SupplyUserExpress::getUserItems(\Yii::$app->user->id)
        ]);
    }

    public function actionDeliver(){
        $orderModel = new OrderModel([
            'scenario' => OrderModel::SCE_SET_DELIVERED,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($orderModel->process()){
            return $this->success([]);
        }else{
            return $this->failure($orderModel->errorCode);
        }
    }

    /**
     *====================================================
     * 订单详情
     * @return string|\yii\web\Response
     * @author shuang.li
     *====================================================
     */
    public function actionDetail(){
        return $this->render('detail');
    }

}
