<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/4
 * Time: 10:18
 */

namespace wechat\controllers;

use wechat\models\ProductModel;

class ProductController extends Controller
{
    protected $access = [
        'info'      => [null, 'get'],
        'cart'      => ['@', 'post'],
        'order'     => ['@', 'post']
    ];

    protected $actionUsingDefaultProcess = [
        'info'      => ProductModel::SCE_GET_INFO,
        'order'     => ProductModel::SCE_PLACE_ORDER,
        '_model'    => ProductModel::class
    ];

    public function actionCart()
    {
        $model = new ProductModel([
            'scenario' => ProductModel::SCE_ADD_CART,
            'attributes' => \Yii::$app->request->post()
        ]);
        if($model->process()){
            return $this->success([]);
        } else {
            return $this->failure($model->getErrorCode());
        }
    }
}