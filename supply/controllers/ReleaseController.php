<?php
namespace supply\controllers;

use Yii;
use common\controllers\Controller;
use supply\models\ReleaseModel;
use yii\helpers\Url;

class ReleaseController extends Controller{

    public $layout = 'main';

    protected $access = [
        'index' => ['@', 'get'],
        'category' => [null, 'get'],
        'attribute' => [null, 'get'],
        'permission' => ['@', 'get'],
        'limit' => ['@', 'get'],
        'product' => ['@', 'post'],
        'info' => ['@', 'get'],
        'full-category' => ['@', 'get'],
        'modify-product' => ['@', 'post'],
        'add-keyword' => ['@', 'post'],
    ];

    protected $actionUsingDefaultProcess = [
        'info' => ReleaseModel::SCE_GET_PRODUCT_INFO,
        'full-category' => ReleaseModel::SCE_GET_FULL_CATEGORY,
        'modify-product' => ReleaseModel::SCE_MODIFY_PRODUCT,
        'add-keyword' => ReleaseModel::SCE_ADD_KEYWORD,
        '_model' => '\supply\models\ReleaseModel',
    ];

    /**
     * 默认跳转至商品分类选择页
     * 当有分类ID时跳转至商品详情填写页
     */
    public function actionIndex(){
        if(ReleaseModel::existEndCategory(Yii::$app->request->get('category', 0))){
            return $this->render('product');
        }else{
            return $this->render('category');
        }
    }

    /**
     * 商品分类
     *
     * @return json
     */
    public function actionCategory(){
        $releaseModel = new ReleaseModel([
            'scenario' => ReleaseModel::SCE_GET_CATEGORY,
            'attributes' => Yii::$app->request->get(),
        ]);
        $category = $releaseModel->getCategory();
        if($category === false){
            return $this->failure($releaseModel->getErrorCode());
        }else{
            return $this->success(['category' => $category]);
        }
    }

    /**
     * 分类属性
     *
     * @return json
     */
    public function actionAttribute(){
        $releaseModel = new ReleaseModel([
            'scenario' => ReleaseModel::SCE_GET_ATTRIBUTE,
            'attributes' => Yii::$app->request->get(),
        ]);
        $attribute = $releaseModel->getAttribute();
        if($attribute === false){
            return $this->failure($releaseModel->getErrorCode());
        }else{
            return $this->success($attribute);
        }
    }

    /**
     * OSS上传授权
     *
     * @return json
     */
    public function actionPermission(){
        $releaseModel = new ReleaseModel([
            'scenario' => ReleaseModel::SCE_GET_PERMISSION,
            'attributes' => Yii::$app->request->get(),
        ]);
        $permission = $releaseModel->getOSSUploadPermission();
        if($permission === false){
            return $this->failure($releaseModel->getErrorCode());
        }else{
            return $this->success($permission);
        }
    }

    /**
     * OSS上传限制
     *
     * @return json
     */
    public function actionLimit(){
        return $this->success(ReleaseModel::getReleaseLimit());
    }

    /**
     * 上传商品
     *
     * @return json
     */
    public function actionProduct(){
        $releaseModel = new ReleaseModel([
            'scenario' => ReleaseModel::SCE_ADD_PRODUCT,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($productId = $releaseModel->addProduct()){
            return $this->success(['url' => Url::to(['/price', 'product_id' => $productId])]);
        }else{
            return $this->failure($releaseModel->getErrorCode());
        }
    }
}
