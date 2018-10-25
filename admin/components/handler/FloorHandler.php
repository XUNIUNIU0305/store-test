<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/6
 * Time: 下午6:21
 */

namespace admin\components\handler;

use common\ActiveRecord\AdminFloorAR;
use common\ActiveRecord\AdminFloorGoodsAR;
use common\ActiveRecord\AdminFloorGroupAR;
use common\components\handler\Handler;
use common\models\parts\Product;
use common\models\parts\supply\SupplyUser;


class FloorHandler extends Handler
{
    /**
     *====================================================
     * 获取楼层商品信息
     * @param $productId
     * @return array
     * @author shuang.li
     *====================================================
     */
    public static function getFloorProduct($productId)
    {
        $product = AdminFloorGoodsAR::find()->select([
            'id',
            'original_id',
            'index_image',
            'view_image',
            'title',
            'sell_point',
            'gid',
            'sort'
        ])->where(['id' => $productId])->orderBy('sort')->asArray()->all();


        $temp = [];
        foreach ($product as $k => $item) {
            $originalProduct                 = new Product(['id' => $item['original_id']]);
            $temp[$k]['gid']                 = $item['gid'];
            $temp[$k]['id']                  = $item['id'];
            $temp[$k]['original_id']         = $originalProduct->id;
            $temp[$k]['original_title']      = $originalProduct->getTitle();
            $temp[$k]['original_sell_point'] = $originalProduct->getDescription();
            $temp[$k]['price']               = $originalProduct->getGuidancePrice();
            $temp[$k]['supplier']            = ($supplyId = $originalProduct->getSupplier()) ? (new SupplyUser(['id' => $supplyId]))->getBrandName() : '';
            $temp[$k]['big_images']          = $originalProduct->getBigImages()->getPath();
            $temp[$k]['index_image']         = $item['index_image'];
            $temp[$k]['view_image']          = $item['view_image'];
            $temp[$k]['title']               = $item['title'];
            $temp[$k]['sell_point']          = $item['sell_point'];
            $temp[$k]['sort']                = $item['sort'];
        }
        return $temp;
    }


    public static function getFloorInfo($floorId)
    {
        $floor = AdminFloorAR::find()->select([
            'id',
            'status',
            'name',
            'color',
            'url',
            'type'
        ])->where(['id' => $floorId])->asArray()->one();

        //楼层组
        $group = AdminFloorGroupAR::find()->select(['id', 'name'])->where(['floor_id' => $floor['id']])->asArray()->all();
        $group = array_column($group, 'name', 'id');

        //组商品
        $product = AdminFloorGoodsAR::find()->select(['id'])->where(['gid' => array_keys($group)])->asArray()->column();
        $product = self::getFloorProduct($product);

        foreach ($group as $key => $value) {

            $res = [];
            foreach ($product as $k => $item) {
                if ($item['gid'] == $key) {
                    $res[] = $item;
                    unset($product[$k]);
                }
            }

            $floor['group'][] = [
                'group_id'   => $key,
                'group_name' => $value,
                'products'   => $res
            ];
        }
//
//        $count = 0;
//        foreach ($group as $k => $v) {
//            $floor['group'][$count]['group_id']   = $k;
//            $floor['group'][$count]['group_name'] = $v;
//            $floor['group'][$count]['products']   = array_slice(array_filter($product, function ($p) use ($k) {
//                return $p['gid'] == $k;
//            }), 1);
//            $count++;
//        }
        return $floor;
    }


    public static function getFloorList()
    {
        return AdminFloorAR::find()->select([
            'id',
            'status',
            'name',
            'color',
            'url',
            'type'
        ])->orderBy('id desc')->asArray()->all();
    }
}