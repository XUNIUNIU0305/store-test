<?php
namespace supply\controllers;

use Yii;
use common\controllers\Controller;
use supply\models\ProductModel;

class ProductController extends Controller{

    public $layout = 'main';

    protected $access = [
        'index' => ['@', 'get'],
        'list' => ['@', 'get'],
        'status' => ['@', 'post'],
    ];

    public function actionIndex(){
        return $this->render('index');
    }

    /**
     * 获取商品列表
     *
     * @return json
     */
    public function actionList(){
        $productModel = new ProductModel([
            'scenario' => ProductModel::SCE_GET_LIST,
            'attributes' => Yii::$app->request->get(),
        ]);
        if($list = $productModel->getList()){
            return $this->success($list, false);
        }else{
            return $this->failure($productModel->getErrorCode());
        }
    }

    /**
     * 修改商品销售状态
     *
     * @return json
     */
    public function actionStatus(){
        $productModel = new ProductModel([
            'scenario' => ProductModel::SCE_MODIFY_SALE_STATUS,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($productModel->modifySaleStatus()){
            return $this->success([]);
        }else{
            return $this->failure($productModel->getErrorCode());
        }
    }
}
