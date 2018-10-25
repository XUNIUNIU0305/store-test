<?php
/**
 * User: JiangYi
 * Date: 2017/5/26
 * Time: 18:23
 * Desc:
 */

namespace mobile\models;

use common\ActiveRecord\ShoppingCartAR;
use common\models\Model;
use Yii;

class CartModel extends Model
{
    const SCE_CHANGE_ITEM = 'change_item';
    const SCE_REMOVE_ITEM = 'remove_item';
    const SCE_GET_LIST = 'get_list';

    public $current_page = 1;
    public $page_size = 10;

    public $item_id;
    public $sku_id;
    public $num;

    public function scenarios()
    {
        $scenario=[
            self::SCE_GET_LIST => [
                'current_page',
                'page_size'
            ],
            self::SCE_CHANGE_ITEM => [
                'sku_id',
                'item_id',
                'num'
            ],
            self::SCE_REMOVE_ITEM => [
                'item_id'
            ]
        ];
        return array_merge(parent::scenarios(),$scenario);
    }

    public function rules()
    {
        return [
            [
                ['item_id', 'sku_id', 'num', 'current_page', 'page_size'],
                'integer',
                'min' => 1,
                'message' => 9002
            ],
            [
                ['item_id'],
                'required'
            ]
        ];
    }

    public function getList()
    {
        try{
            return CartItem::queryItemsGroupBySupply(\Yii::$app->user->id, $this->current_page, $this->page_size);
        } catch (\Exception $e){
            $this->addError('list', 15001);
            return false;
        }
    }

    /**
     * 修改购物车商品
     * @return bool
     */
    public function changeItem()
    {
        if(!$cartItem = CartItem::getInstanceById($this->item_id)){
            $this->addError('item_id', 9002);
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if($this->sku_id && $cartItem->product_sku_id != $this->sku_id){
                //已修改sku
                $cartItem->updateSkuId($this->sku_id, $this->num);
                //删除相同商品
                $uid = Yii::$app->user->id;
                Yii::$app->db->createCommand('delete from ' . ShoppingCartAR::tableName() .' where product_sku_id = :sku_id and custom_user_id = :uid and id <> :id')
                    ->bindParam('sku_id', $this->sku_id)
                    ->bindParam('uid', $uid)
                    ->bindParam('id', $this->item_id)
                    ->execute();
            }
            //修改数量
            if($this->num && $cartItem->count != $this->num){
                $cartItem->updateCount($this->num);
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e){
            $transaction->rollBack();
            $this->addError('updateItem', 10003);
            return false;
        }
    }

    /**
     * 购物车删除
     * @return bool
     */
    public function removeItem()
    {
        $item = CartItem::getInstanceById($this->item_id);
        if($item->getCustomUid() == Yii::$app->getUser()->id){
            $item->updateCount(0);
            return true;
        }
        $this->addError('item_id', 9002);
        return false;
    }
}