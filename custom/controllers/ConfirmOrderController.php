<?php
namespace custom\controllers;

use Yii;
use common\controllers\Controller;
use custom\models\ConfirmOrderModel;

class ConfirmOrderController extends Controller{

    public $layout = 'header_footer_order';

    protected $access = [
        'index' => ['@', 'get'],
        'list' => ['@', 'get'],
        'confirm' => ['@', 'post'],
        'payment' => ['@', 'get'],
        'get-tickets' => ['@', 'get'],
        'get-suitable-tickets' => ['@', 'get'],
        'get-reduction' => ['@', 'get'],
    ];

    protected $actionUsingDefaultProcess = [
        'get-tickets' => [
            'scenario' => ConfirmOrderModel::SCE_GET_TICKETS,
            'convert' => false,
        ],
        'get-suitable-tickets' => [
            'scenario' => ConfirmOrderModel::SCE_GET_SUITABLE_TICKETS,
            'convert' => false,
        ],
        //临时功能-满减
        'get-reduction' => ConfirmOrderModel::SCE_GET_REDUCTION,

        '_model' => ConfirmOrderModel::class,
    ];

    public function actionPayment(){
        return $this->success(ConfirmOrderModel::getPaymentMethod());
    }

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionList(){
        $confirmOrderModel = new ConfirmOrderModel([
            'scenario' => ConfirmOrderModel::SCE_GET_LIST,
            'attributes' => Yii::$app->request->get(),
        ]);
        if($result = $confirmOrderModel->process()){
            return $this->success($result);
        }else{
            return $this->failure($confirmOrderModel->getErrorCode());
        }
    }

    public function actionConfirm(){
        Yii::$app->db->queryMaster = true;
        $confirmOrderModel = new ConfirmOrderModel([
            'scenario' => ConfirmOrderModel::SCE_GENERATE_TRADE,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($result = $confirmOrderModel->process()){
            return $this->success($result);
        }else{
            return $this->failure($confirmOrderModel->getErrorCode());
        }
    }
}
