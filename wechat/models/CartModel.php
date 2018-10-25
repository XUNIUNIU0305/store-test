<?php
/**
 * User: JiangYi
 * Date: 2017/5/26
 * Time: 18:23
 * Desc:
 */

namespace wechat\models;

use common\models\parts\Item;
use custom\models\parts\ItemInCart;
use Yii;
use yii\base\InvalidCallException;
use yii\base\InvalidParamException;

class CartModel extends \custom\models\CartModel
{
    const  SCE_UPDATE_ITEMS="update_items";


    public $items;


    public function scenarios()
    {
        $scenario=[
            self::SCE_UPDATE_ITEMS=>['items'],
        ];
        return array_merge(parent::scenarios(),$scenario);
    }

    public function rules()
    {
        $rules=[
            [
                ['items'],
                'each',
                'rule'=>[
                    'common\validators\item\ItemsValidator',
                    'userId' => Yii::$app->user->id,
                    'stockOverFlowMessage'=>10004,

                ],
                'message'=>10003,
            ],
        ];
        return array_merge(parent::rules(),$rules);
    }


    /**
     * Author:JiangYi
     * Date:2017/5/27
     * Desc:批量修改购物车信息
     * @return bool
     */
    public function updateItems(){
        $transaction=Yii::$app->db->beginTransaction();
        try{
            foreach($this->items as $item){
                if($item['item_id']==$item['sku_id']){

                    //未更换sku时，公更新订购数量
                    $itemObject=new ItemInCart(['id'=>$item['item_id']]);


                    $count=$itemObject->getCount();
                    //  echo $count.'    '.$item['quantity'];
                    if($count<$item['quantity']){
                        Yii::$app->CustomUser->cart->addItem(new Item(['id'=>$item['item_id']]),$item['quantity']-$count);
                    }elseif($count>$item['quantity']){
                        Yii::$app->CustomUser->cart->removeItem($itemObject,$count-$item['quantity']);
                    }

                }else{
                    //更新购物车项商品SKU属性
                    $itemObject=new ItemInCart(['id'=>$item['item_id']]);
                    $sku=new Item(['id'=>$item["sku_id"]]);
                    Yii::$app->CustomUser->cart->resetCartSku($itemObject,$sku,$item['quantity']);
                }
            }
            $transaction->commit();
            return true;
        }catch (\Exception $e){

            $transaction->rollBack();
            $this->addError('updateItems',10003);
            return false;
        }
    }


}