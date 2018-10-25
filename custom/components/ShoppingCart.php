<?php
namespace custom\components;

use common\ActiveRecord\ShoppingCartAR;
use common\models\parts\Item;
use common\models\parts\Supplier;
use common\models\RapidQuery;
use common\traits\ErrCallbackTrait;
use custom\models\parts\ItemInCart;
use Yii;
use yii\base\InvalidCallException;
use yii\base\Object;
use yii\data\ActiveDataProvider;
use common\models\parts\Product;

class ShoppingCart extends Object
{

    use ErrCallbackTrait;

    //custom_user表 ID
    protected $userId;

    public function init()
    {
        if (Yii::$app->user->isGuest) throw new InvalidCallException;
        $this->userId = Yii::$app->user->id;
    }


    /**
     * Author:JiangYi
     * Date:2017/05/27
     * Desc:重置购物车商品属性信息
     * @param ItemInCart $item
     * @param Item $sku
     * @param int $quantity
     * @param string $return
     * @return mixed
     */
    public function resetCartSku(ItemInCart $item, Item $sku, $quantity = 1, $return = "throw")
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($this->existItem($sku->id)) {
                //调整原有购物车项数量，然后清除本记录
                if (!$this->addItem($sku, $quantity) || !$this->removeItem($item)) {
                    throw new InvalidCallException();
                }
                $transaction->commit();
                return true;
            }else {
                Yii::$app->RQ->AR(ShoppingCartAR::findOne([
                    'custom_user_id' => $this->userId,
                    'product_sku_id' => $item->id,
                ]))->update([
                    'product_id' => $sku->getProductId(),
                    'product_sku_id' => $sku->id,
                    'count' => $quantity,
                ], $return);

                $transaction->commit();
                return true;
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return true;
        }
    }


    /**
     * 提供item ID
     *
     * @param $currentPage 当前页数
     * @param $pageSize 每页数量
     *
     * @return Object ActiveDataProvider
     */
    public function provideItems($currentPage, $pageSize)
    {
        if (!$currentPage = (int)$currentPage) $currentPage = 1;
        if (!$pageSize = (int)$pageSize) $pageSize = 1;
        return new ActiveDataProvider([
            'query' => ShoppingCartAR::find()->select(['id', 'product_sku_id'])->where([
                'custom_user_id' => $this->userId,
            ])->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'add_unixtime' => SORT_DESC,
                ],
            ],
        ]);
    }

    /**
     * 获取根据供应商分组的Items
     *
     * @param array $itemsId =null item ID 为NULL时获取全部item
     *
     * @return array
     */
    public function getItemsGroupBySuppliers(array $itemsId = null)
    {
        $items = $this->getItems($itemsId);
        $groupedItems = [];
        foreach ($items as $item) {
            $groupedItems[$item->supplier][] = $item;
        }
        $itemsGroupBySuppliers = [];
        foreach ($groupedItems as $supplier => $oneGroupItems) {
            $itemsGroupBySuppliers[] = [
                'supplier' => new Supplier(['id' => $supplier]),
                'items' => $oneGroupItems,
            ];
        }
        return $itemsGroupBySuppliers;
    }

    /**
     * 获取根据订单分组的Items
     *
     * @param array $itemsId
     *
     * @return array
     */
    public function getItemsGroupByOrders(array $itemsId = null){
        if($items = $this->getItemsGroupBySuppliers($itemsId)){
            foreach($items as &$supplier){
                $splitItems = [
                    Product::TYPE_STANDARD => [],
                    Product::TYPE_CUSTOMIZATION => [],
                ];
                foreach($supplier['items'] as $item){
                    if($item->productObj->customization){
                        $splitItems[Product::TYPE_CUSTOMIZATION][] = $item;
                    }else{
                        $splitItems[Product::TYPE_STANDARD][] = $item;
                    }
                }
                $supplier['items'] = $splitItems;
            }
            return $items;
        }else{
            return [];
        }
    }

    /**
     * 获取Items
     *
     * @param array $itemsId =null item ID 为NULL时获取全部item
     *
     * @return array
     */
    public function getItems(array $itemsId = null)
    {
        $itemsInCart = (new RapidQuery(new ShoppingCartAR))->column([
            'select' => ['product_sku_id'],
            'where' => ['custom_user_id' => $this->userId],
        ]);
        $itemsId = is_null($itemsId) ? $itemsInCart : array_intersect($itemsInCart, $itemsId);
        return array_values(array_map(function ($itemId) {
            return new ItemInCart(['id' => $itemId]);
        }, $itemsId));
    }

    /**
     * 检查购物车内是否有指定Item
     *
     * @return boolean
     */
    public function existItem(int $itemId)
    {
        return Yii::$app->RQ->AR(new ShoppingCartAR)->exists([
            'where' => [
                'custom_user_id' => $this->userId,
                'product_sku_id' => $itemId,
            ],
            'limit' => 1,
        ]);
    }

    /**
     * 获取购物车内商品种类
     *
     * @return integer
     */
    public function getItemsQuantity($itemId = 0)
    {
        $where = [
            'custom_user_id' => $this->userId,
        ];
        if ($itemId > 0) {
            $where['product_sku_id'] = $itemId;
        }
        return (new RapidQuery(new ShoppingCartAR))->count([
            'where' => $where
        ]);
    }

    public function getAllQuantity(){
        return Yii::$app->RQ->AR(new ShoppingCartAR)->sum([
            'where' => [
                'custom_user_id' => $this->userId,
            ],
        ], 'count') ? : 0;
    }

    /**
     * 添加Item
     *
     * 当购物车内没有该Item则新增Item
     * 当购物车内有该Item则增加该Item的数量
     * 如果$count或者购物车内该Item增加后的数量大于Item的库存，则将购物车内该Item的数量强制修改为最大库存
     *
     * @param Item $item
     * @param int $count =1
     *
     * @return boolean
     */
    public function addItem(Item $item, int $count = 1, $return = 'throw'){
        //邀请门店强制提交信息
        //if(!Yii::$app->CustomUser->CurrentUser->isAuthorized)return Yii::$app->EC->callback($return, 'the user have not been authorized yet');
        if(Yii::$app->CustomUser->CurrentUser->level < $item->productObj->customerLimit)return Yii::$app->EC->callback($return, 'this item can just be bought by higher level user');
        if ($count <= 0) return $this->errCallback($return, 'P_int');
        if ($shoppingCartAR = ShoppingCartAR::findOne(['custom_user_id' => $this->userId, 'product_sku_id' => $item->id])) {
            return $this->increaseItemQuantity(new ItemInCart(['id' => $item->id]), $shoppingCartAR, $count, $return);
        } else {
            return $this->addNewItem($item, $count, $return);
        }
    }

    /**
     * 移除Item
     *
     * 当$count为零时，将该Item从购物车中删除
     * 当$count大于购物车内该Item的数量时，强制将该Item的数量修改为1
     *
     * @param ItemInCart $item
     * @param int $count =0
     *
     * @return boolean
     */
    public function removeItem(ItemInCart $item, int $count = 0, $return = 'throw')
    {
        if ($count < 0) return $this->errCallback($return, 'P_int');
        if ($count == 0) {
            return $this->clearItem($item, $return);
        } else {
            $shoppingCartAR = ShoppingCartAR::findOne(['custom_user_id' => $this->userId, 'product_sku_id' => $item->id]);
            return $this->decreaseItemQuantity($item, $shoppingCartAR, $count, $return);
        }
    }

    /**
     * 购物车中新增加Item
     *
     * $count将被修正
     *
     * @param Item $item
     * @param $count =1
     *
     * @return boolean
     */
    protected function addNewItem(Item $item, $count = 1, $return = 'throw'){
        $count = $count >= $item->stock ? $item->stock : $count;
        return (new RapidQuery(new ShoppingCartAR))->insert([
            'custom_user_id' => $this->userId,
            'product_id' => $item->productId,
            'product_sku_id' => $item->id,
            'count' => $count,
        ], $return);
    }

    /**
     * 购物车中删除Item
     *
     * @param ItemInCart $item
     *
     * @return boolean
     */
    protected function clearItem(ItemInCart $item, $return = 'throw')
    {
        $shoppingCartAR = ShoppingCartAR::findOne(['custom_user_id' => $item->userId, 'product_sku_id' => $item->id]);
        return Yii::$app->RQ->AR($shoppingCartAR)->delete($return);
    }

    /**
     * 增加购物车内Item的数量
     *
     * $count将被修正
     *
     * @param ItemInCart $item
     * @param ShoppingCartAR $AR
     * @param $count =1
     *
     * @return boolean
     */
    protected function increaseItemQuantity(ItemInCart $item, ShoppingCartAR $AR, $count = 1, $return = 'throw')
    {
        $count = $count + $AR->count >= $item->stock ? $item->stock - $AR->count : $count;
        return $AR->updateCounters(['count' => $count]);
        if ($queryResult = $AR->updateCounters(['count' => $count])) {
            return $queryResult;
        } else {
            return $this->errCallback($return, 'increase item quantity failed');
        }
    }

    /**
     * 减少购物车内Item的数量
     *
     * $count将被修正
     *
     * @param ItemInCart $item
     * @param ShoppingCartAR $AR
     * @param $count =1
     *
     * @return boolean
     */
    protected function decreaseItemQuantity(ItemInCart $item, ShoppingCartAR $AR, $count = 1, $return = 'throw')
    {
        if ($count >= $AR->count) {
            if ($AR->count - 1 <= 0) {
                $count = 0;
            } else {
                $count = $AR->count - 1;
            }
        }
        return $AR->updateCounters(['count' => $count * -1]);
        if ($queryResult = $AR->updateCounters(['count' => $count * -1])) {
            return $queryResult;
        } else {
            return $this->errCallback($return, 'decrease item quantity failed');
        }
    }
}
