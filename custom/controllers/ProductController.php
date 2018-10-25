<?php
namespace custom\controllers;

use Yii;
use common\controllers\Controller;
use custom\models\ProductModel;

class ProductController extends Controller{

    public $layout = 'header_footer_search';

    protected $access = [
        'index' => ['@', 'get'],
        'info' => ['@', 'get'],
        'cart' => ['@', 'post'],
        'order' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'order' => ProductModel::SCE_PLACE_ORDER,
        '_model' => 'custom\models\ProductModel',
    ];

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionInfo(){
        $productModel = new ProductModel([
            'scenario' => ProductModel::SCE_GET_INFO,
            'attributes' => Yii::$app->request->get(),
        ]);
        if($info = $productModel->process()){
            return $this->success($info);
        }else{
            return $this->failure($productModel->getErrorCode());
        }
    }

    public function actionCart(){
        $productModel = new ProductModel([
            'scenario' => ProductModel::SCE_ADD_CART,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($productModel->process()){
            return $this->success([]);
        }else{
            return $this->failure($productModel->getErrorCode());
        }
    }
}
