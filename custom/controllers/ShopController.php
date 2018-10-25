<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/5/8
 * Time: 上午10:01
 */

namespace custom\controllers;


use common\controllers\Controller;
use common\models\parts\supply\SupplyShop;
use custom\models\ShopModel;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ShopController extends Controller
{

    public $layout = 'header_footer_search';

    protected $access = [
        'index' => ['@', 'get'],
        'product-list' => ['@', 'get'],
        'shop-adv-list' => ['@', 'get']
    ];

    public function actionIndex()
    {
        return $this->render("index");
    }

    public function actionShopAdvList(){
        $getArr = Yii::$app->request->get();
        try{
            $shop = new SupplyShop(['id' => $getArr['supply_user_id']]);
            $data = ArrayHelper::merge(['supply'=>['brandName'=>$shop->brandName,'headerImg'=>$shop->headerImg,'province'=>$shop->getProvince(true)->getName()]],$shop->getShopAdv());
            return $this->success($data);;
        }catch (\Exception $e){
            throw  new NotFoundHttpException();
        }

    }

    public function actionProductList(){
        $shopModel = new ShopModel([
            'scenario' => ShopModel::SCE_SHOP_LIST,
            'attributes' => Yii::$app->request->get(),
        ]);
        if (($shopList = $shopModel->process()) == false){
            throw  new NotFoundHttpException();
        }
        return $this->success($shopList);
    }
}