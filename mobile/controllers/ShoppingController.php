<?php
/**
 * User: JiangYi
 * Date: 2017/5/19
 * Time: 9:56
 * Desc:
 */

namespace mobile\controllers;


use custom\models\CartModel;
use custom\models\ConfirmOrderModel;
use custom\models\ProductModel;
use Yii;
use yii\helpers\Url;

class ShoppingController extends Controller
{

    protected  $access=[
        'index'=>['@','get'],//待定
        'confirm-order'=>['@','get'],//购物确认页面
//        'get-cart-list'=>['@','get'],//获取商品详情
//        'remove-item'=>['@','post'],//删除购物车选择
//        'change-item-count'=>['@','post'],//更新购物车选项数量
        'place-order'=>['@','post'],//提交订单 创建字加密字符串
        'get-order-item'=>['@','get'],//获取订购确认项
        'create-order'=>['@','post'],//创建订单
        'add-cart'=>['@','post'],//添加购物车
        'order'=>['@','post'],//立即购买
        //'update-cart'=>['@','post'],//批量更新购物车选项
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
        'get-reduction' => ConfirmOrderModel::SCE_GET_REDUCTION,
        '_model' => ConfirmOrderModel::class,
    ];


    /**
     * Author:JiangYi
     * Date:2017/5/26
     * Desc:立即购买
     * @return \common\controllers\json
     */
    public function actionOrder(){
        $productModel = new ProductModel([
            'scenario' => ProductModel::SCE_PLACE_ORDER,
            'attributes' => Yii::$app->request->post(),
        ]);
        if($result=$productModel->process()){
             return $this->success($result);
        }else{
            return $this->failure($productModel->getErrorCode());
        }
    }

    /**
     * Author:JiangYi
     * Date:2017/5/26
     * Desc:加入购物车
     * @return \common\controllers\json
     */
    public function actionAddCart(){
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

    /**
     * Author:JiangYi
     * Date:2017/5/26
     * Desc:提交订单选项
     * @return \common\controllers\json
     */
    public function actionPlaceOrder(){

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

    /**
     * Author:JiangYi
     * Date:2017/5/19
     * Desc:确认订单页面输出
     * @return string
     */
    public function actionConfirmOrder(){
        return $this->render('confirm_order');
    }


    /**
     * Author:JiangYi
     * Date:2017/5/19
     * Desc:创建用户订单
     * @return \common\controllers\json
     */
    public function actionCreateOrder(){
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


    /**
     * Author:JiangYi
     * Date:2017/5/19
     * Desc:获取用户选中订购项列表
     * @return \common\controllers\json
     */
    public function actionGetOrderItem(){
        $confirmOrderModel = new ConfirmOrderModel([
            'scenario' => ConfirmOrderModel::SCE_GET_LIST,
            'attributes' => \Yii::$app->request->get(),
        ]);
        if($result = $confirmOrderModel->process()){
            return $this->success($result);
        }else{
            return $this->failure($confirmOrderModel->getErrorCode());
        }
    }


    /**
     * Author:JiangYi
     * Date:2017/5/19
     * Desc:购物车
     * @return string
     */
    public function actionIndex(){
        return $this->render('index');
    }


}
