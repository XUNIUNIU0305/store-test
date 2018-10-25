<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/4
 * Time: 9:06
 */

namespace wechat\controllers;

use Yii;
use wechat\models\CartModel;
use yii\helpers\Url;

class CartController extends Controller
{
    protected $access = [
        'list'      => ['@', 'get'],
        'change'    => ['@', 'post'],
        'remove'    => ['@', 'post'],
        'order'     => ['@', 'post'],
        'quantity'  => ['@', 'get']
    ];

    protected $actionUsingDefaultProcess = [
        'list'      => CartModel::SCE_GET_LIST,
        '_model'=>'mobile\models\CartModel',
    ];

    public function actionRemove()
    {
        $cartModel = new CartModel([
            'scenario' => CartModel::SCE_REMOVE_ITEMS,
            'attributes' => Yii::$app->request->post()
        ]);
        if($cartModel->process()){
            return $this->success([]);
        }else{
            return $this->failure($cartModel->getErrorCode());
        }
    }

    public function actionChange()
    {
        $model = new CartModel([
            'scenario' => CartModel::SCE_CHANGE_ITEM_COUNT,
            'attributes' => Yii::$app->request->post()
        ]);
        if($result = $model->process()){
            return $this->success($result);
        }else{
            return $this->failure($model->getErrorCode());
        }
    }

    /**
     * @return string
     */
    public function actionOrder()
    {
        $cartModel = new CartModel([
            'scenario' => CartModel::SCE_PLACE_ORDER,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($result = $cartModel->process()){
            return $this->success(['q' => $result]);
        }else{
            return $this->failure($cartModel->getErrorCode());
        }
    }

    /**
     * 获取购物车内商品种类
     * @return string
     */
    public function actionQuantity()
    {
        return $this->success(CartModel::getItemsQuantity());
    }
}