<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-9-20
 * Time: 上午11:17
 */

namespace mobile\models;


use common\ActiveRecord\ProductAR;
use common\ActiveRecord\ProductBigImagesAR;
use common\ActiveRecord\ProductSKUAR;
use common\ActiveRecord\ShoppingCartAR;
use common\ActiveRecord\SupplyUserAR;
use yii\data\Pagination;
use common\models\Object;
use yii\web\BadRequestHttpException;

/**
 * Class CartItem
 * @package wechat\models
 * @property $product_sku_id int|null
 * @property $count int|null
 * @property $custom_user_id
 * @property $product_id
 */
class CartItem extends Object
{
    /**
     * @param $id
     * @return static
     */
    public static function getInstanceById($id)
    {
        if($ar = ShoppingCartAR::findOne($id)){
            return new static(['ar' => $ar]);
        }
    }

    /**
     * 修改数量
     * @param $num
     * @return false|int
     */
    public function updateCount($num)
    {
        if($num <= 0){
            return $this->AR->delete();
        }
        $stock = $this->getSku()->stock;
        $num = $num > $stock ? $stock : $num;
        return $this->AR->updateAttributes(['count' => $num]);
    }

    private $_sku;
    /**
     * @return Sku|null
     */
    public function getSku()
    {
        if($this->_sku === null){
            $this->_sku = Sku::getInstanceById($this->product_sku_id);
        }
        return $this->_sku;
    }

    /**
     * 购买用户iD
     * @return mixed
     */
    public function getCustomUid()
    {
        return $this->AR->custom_user_id;
    }

    /**
     * @param $id
     * @param int $num
     * @throws BadRequestHttpException
     * @throws \Exception
     */
    public function updateSkuId($id, $num = 0)
    {
        if(!$sku = Sku::getInstanceById($id))
            throw new BadRequestHttpException('无效商品属性');
        if($sku->product_id != $this->product_id)
            throw new BadRequestHttpException('无效商品属性');

        $this->_sku = $sku;
        $num = $num ? $num : $this->count;
        $this->AR->updateAttributes([
            'product_sku_id' => $id,
            'count' => $num> $sku->stock ? $sku->stock : $num
        ]);
    }

    public static function queryItemsGroupBySupply($uid, $page = 1, $pageSize = 10)
    {
        $query = ShoppingCartAR::find()->where(['custom_user_id' => $uid]);
        $count = clone $query;
        $page = new Pagination([
            'totalCount' => $count->count(),
            'page' => $page - 1,
            'pageSize' => $pageSize
        ]);

        /** @var ShoppingCartAR[] $cartItems */
        $cartItems = $query->offset($page->offset)->limit($page->limit)
            ->select(['id', 'product_id', 'product_sku_id', 'count', 'add_datetime'])->asArray()->all();
        if(empty($cartItems)) return [];

        //查询sku
        $skuItems = ProductSKUAR::find()->where(['id' => array_column($cartItems, 'product_sku_id')])
            ->indexBy('id')->all();

        //商品属性
        $skuItems = Sku::getInstancesByArray($skuItems);

        //商品id
        $productId = array_map(function($item){
            return $item->product_id;
        }, $skuItems);
        //商品
        $productItems = ProductAR::find()
            ->select(['id', 'supply_user_id', 'title', 'sale_status'])
            ->where(['id' => $productId])
            ->indexBy('id')
            ->asArray()->all();
        //商品主图
        $mainImg = ProductBigImagesAR::find()
            ->select(['filename', 'product_id'])
            ->where(['product_id' => $productId])
            ->andwhere(['sort' => 0])
            ->indexBy('product_id')
            ->orderBy('sort asc')
            ->column();
        $group = [];
        foreach ($productItems as $val){
            $group[$val['supply_user_id']][] = $val['id'];
        }
        //获取供应商
        $supplyBrandName = SupplyUserAR::find()
            ->select(['brand_name', 'id'])
            ->where(['id' => array_keys($group)])
            ->indexBy('id')
            ->column();
        $hostname = \Yii::$app->params['OSS_PostHost'];
        foreach ($group as $key=>&$value){
            $supply = ['supplier' => $supplyBrandName[$key]];
            $supply['items'] = [];
            foreach ($cartItems as $k=>$cartItem){
                if(in_array($cartItem['product_id'], $value)){
                    $sku = $skuItems[$cartItem['product_sku_id']];
                    $product = $productItems[$cartItem['product_id']];
                    $image = $mainImg[$cartItem['product_id']];
                    $cartItem['price'] = $sku->price;
                    $cartItem['stock'] = $sku->stock;
                    $cartItem['attributes'] = $sku->skuAttributes;
                    $cartItem['sku_cartesian'] = $sku->sku_cartesian;
                    $cartItem['title'] = $product['title'];
                    $cartItem['sale_status'] = $product['sale_status'];
                    $cartItem['image'] = $hostname . '/' . $image . '?x-oss-process=image/resize,w_80,h_80';
                    $supply['items'][] = $cartItem;
                    unset($cartItems[$k]);
                }
            }
            $value = $supply;
        }
        return [
            'total_count' => $page->totalCount,
            'count' => $pageSize,
            'items' => array_values($group)
        ];
    }
}