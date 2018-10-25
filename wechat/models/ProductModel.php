<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/4
 * Time: 10:20
 */

namespace wechat\models;

use Yii;
use custom\models\parts\ItemInCart;
use common\models\parts\Item;
use custom\models\parts\UrlParamCrypt;

class ProductModel extends \custom\models\ProductModel
{
    public function placeOrder()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(Yii::$app->CustomUser->cart->existItem((int)$this->sku_id)){
                Yii::$app->CustomUser->cart->removeItem(new ItemInCart(['id' => $this->sku_id]));
            }
            Yii::$app->CustomUser->cart->addItem(new Item(['id' => $this->sku_id]), $this->count);
            $transaction->commit();
            $urlCrypt = new UrlParamCrypt;
            return [
                'q' =>$urlCrypt->encrypt((array)$this->sku_id)
            ];
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('placeOrder', 3141);
            return false;
        }
    }
}