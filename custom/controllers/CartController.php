<?php
namespace custom\controllers;

use Yii;
use common\controllers\Controller;
use custom\models\CartModel;
use yii\helpers\Url;

class CartController extends Controller{

    public $layout = 'header_footer_search';

    protected $access = [
        'index' => ['@', 'get'],
        'list' => ['@', 'get'],
        'change' => ['@', 'post'],
        'remove' => ['@', 'post'],
        'order' => ['@', 'post'],
        'quantity' => ['@', 'get'],
    ];

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionList(){
        $cartModel = new CartModel([
            'scenario' => CartModel::SCE_GET_LIST,
            'attributes' => Yii::$app->request->get(),
        ]);
        $result = $cartModel->process();
        if($result === false){
            return $this->failure($cartModel->getErrorCode());
        }else{
            return $this->success($result);
        }
    }

    public function actionChange(){
        $cartModel = new CartModel([
            'scenario' => CartModel::SCE_CHANGE_ITEM_COUNT,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($result = $cartModel->process()){
            return $this->success($result);
        }else{
            return $this->failure($cartModel->getErrorCode());
        }
    }

    public function actionRemove(){
        $cartModel = new CartModel([
            'scenario' => CartModel::SCE_REMOVE_ITEMS,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($cartModel->process()){
            return $this->success([]);
        }else{
            return $this->failure($cartModel->getErrorCode());
        }
    }

    public function actionOrder(){
        $cartModel = new CartModel([
            'scenario' => CartModel::SCE_PLACE_ORDER,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($result = $cartModel->process()){
            return $this->success(['url' => Url::to(['/confirm-order', 'q' => $result])]);
        }else{
            return $this->failure($cartModel->getErrorCode());
        }
    }

    public function actionQuantity(){
        return $this->success(CartModel::getItemsQuantity());
    }
}
