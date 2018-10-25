<?php
namespace supply\controllers;

use Yii;
use common\controllers\Controller;
use supply\models\PriceModel;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

class PriceController extends Controller{

    public $layout = 'main';

    protected $access = [
        'index' => ['@', 'get'],
        'split' => ['@', 'get'],
        'sku' => ['@', 'post'],
        'current-sku' => ['@', 'get'],
        'modify-sku' => ['@', 'post'],
        'sale-price' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'sale-price' => PriceModel::SCE_CALCULATE_PRICE,
        '_model' => 'supply\models\PriceModel',
    ];

    /**
     * 显示页面
     *
     * 如果商品id错误则报错404
     */
    public function actionIndex(){
        $priceModel = new PriceModel([
            'scenario' => PriceModel::SCE_SHOW_PAGE,
            'attributes' => Yii::$app->request->get(),
        ]);
        if($priceModel->validate()){
            return $this->render('index');
        }else{
            throw new NotFoundHttpException;
        }
    }

    /**
     * 获取分隔符
     *
     * @return json
     */
    public function actionSplit(){
        return $this->success(PriceModel::getCartesianSplit());
    }

    /**
     * 上传sku
     *
     * @return json
     */
    public function actionSku(){
        $priceModel = new PriceModel([
            'scenario' => PriceModel::SCE_ADD_SKU,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($priceModel->addSKU()){
            return $this->success(['url' => Url::to(['/product'])]);
        }else{
            return $this->failure($priceModel->getErrorCode());
        }
    }

    /**
     * 获取商品当前sku
     *
     * @return json
     */
    public function actionCurrentSku(){
        $priceModel = new PriceModel([
            'scenario' => PriceModel::SCE_CURRENT_SKU,
            'attributes' => Yii::$app->request->get(),
        ]);
        $sku = $priceModel->getCurrentSKU();
        if($sku === false){
            return $this->failure($priceModel->getErrorCode());
        }else{
            return $this->success($sku);
        }
    }

    /**
     * 修改商品sku
     *
     * @return json
     */
    public function actionModifySku(){
        $priceModel = new PriceModel([
            'scenario' => PriceModel::SCE_MODIFY_SKU,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($priceModel->modifySKU()){
            return $this->success(['url' => Url::to(['/product'])]);
        }else{
            return $this->failure($priceModel->getErrorCode());
        }
    }
}
