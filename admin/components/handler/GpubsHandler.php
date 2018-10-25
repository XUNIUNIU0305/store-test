<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-6-22
 * Time: 上午5:45
 */

namespace admin\components\handler;

use common\ActiveRecord\ActivityGpubsGroupDetailAR;
use common\ActiveRecord\ActivityGpubsProductAR;
use common\ActiveRecord\ActivityGpubsProductSkuAR;
use common\ActiveRecord\ProductAR;
use common\ActiveRecord\ProductSKUAR;
use common\components\handler\Handler;
use common\models\parts\gpubs\GpubsProduct;
use common\models\parts\Product;
use common\models\RapidQuery;
use Yii;
use yii\data\ActiveDataProvider;

class GpubsHandler extends Handler
{
    const GPUBS_TYPE_ALL = 3;

    public static function create($product_id, $max_launch_per_user, $lifecycle_per_group, $start_datetime, $end_datetime, $min_quantity_per_group, $description, $share_title, $share_subtitle, $filename)
    {
        $start_unixtime = strtotime($start_datetime);
        $end_unixtime = strtotime($end_datetime);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $id = Yii::$app->RQ->AR(new ActivityGpubsProductAR())->insert([
                'product_id' => $product_id,
                'max_launch_per_user' => $max_launch_per_user,
                'activity_start_datetime' => $start_datetime,
                'activity_start_unixtime' => $start_unixtime,
                'activity_end_datetime' => $end_datetime,
                'activity_end_unixtime' => $end_unixtime,
                'lifecycle_per_group' => $lifecycle_per_group * 3600,
                'min_quantity_per_group' => $min_quantity_per_group,
                'status' => GpubsProduct::STATUS_ACTIVE,
                'description' => $description,
                'share_title' => $share_title,
                'share_subtitle' => $share_subtitle,
                'filename' => $filename,
            ]);
            $gpubsProduct = new GpubsProduct(['id' => $id]);
            $transaction->commit();
            return $gpubsProduct;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    public static function createDeliver($product_id, $max_launch_per_user, $lifecycle_per_group, $start_datetime, $end_datetime, $min_quantity_per_group, $description, $share_title, $share_subtitle, $filename, int $gpubs_type, int $gpubs_rule_type, $min_member_per_group, $min_quantity_per_member_of_group)
    {
        $start_unixtime = strtotime($start_datetime);
        $end_unixtime = strtotime($end_datetime);
        switch ($gpubs_rule_type) {
            case GpubsProduct::STATUS_GPUBS_RULE_MEMBER :
                $min_quantity_per_group = 0;
                $min_quantity_per_member_of_group = 0;
                break;
            case GpubsProduct::STATUS_GPUBS_RULE_NUMBER :
                $min_member_per_group = 0;
                $min_quantity_per_member_of_group = 0;
                break;
            case GpubsProduct::STATUS_GPUBS_PRE_NUMBER :
                $min_quantity_per_group = 0;
                break;
            default:
                return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $id = Yii::$app->RQ->AR(new ActivityGpubsProductAR())->insert([
                'product_id' => $product_id,
                'max_launch_per_user' => $max_launch_per_user,
                'activity_start_datetime' => $start_datetime,
                'activity_start_unixtime' => $start_unixtime,
                'activity_end_datetime' => $end_datetime,
                'activity_end_unixtime' => $end_unixtime,
                'lifecycle_per_group' => $lifecycle_per_group * 3600,
                'min_quantity_per_group' => $min_quantity_per_group,
                'status' => GpubsProduct::STATUS_ACTIVE,
                'description' => $description,
                'share_title' => $share_title,
                'share_subtitle' => $share_subtitle,
                'filename' => $filename,
                'gpubs_type' => $gpubs_type,
                'gpubs_rule_type' => $gpubs_rule_type,
                'min_member_per_group' => $min_member_per_group,
                'min_quantity_per_member_of_group' => $min_quantity_per_member_of_group,
            ]);
            $gpubsProduct = new GpubsProduct(['id' => $id]);
            $transaction->commit();
            return $gpubsProduct;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    public static function update($gpubsProductId, $product_id, $max_launch_per_user, $lifecycle_per_group, $start_datetime, $end_datetime, $min_quantity_per_group)
    {
        $start_unixtime = strtotime($start_datetime);
        $end_unixtime = strtotime($end_datetime);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            Yii::$app->RQ->AR(ActivityGpubsProductAR::findOne($gpubsProductId))->update([
                'product_id' => $product_id,
                'max_launch_per_user' => $max_launch_per_user,
                'activity_start_datetime' => $start_datetime,
                'activity_start_unixtime' => $start_unixtime,
                'activity_end_datetime' => $end_datetime,
                'activity_end_unixtime' => $end_unixtime,
                'lifecycle_per_group' => $lifecycle_per_group * 3600,
                'min_quantity_per_group' => $min_quantity_per_group,
                'status' => GpubsProduct::STATUS_ACTIVE,
            ]);
            $transaction->commit();
            return new GpubsProduct(['id' => $gpubsProductId]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    public static function getSkuDetail($productId)
    {
        $skus = (new RapidQuery(new ProductSKUAR))->all([
            'select' => ['id', 'sku_cartesian', 'price AS original_price'],
            'where' => ['product_id' => $productId],
        ]);
        $gpubsSkus = (new RapidQuery(new ActivityGpubsProductSkuAR))->all([
            'select' => ['product_sku_id', 'price', 'stock'],
            'where' => ['product_id' => $productId],
        ]);
        $allSkus = [];
        foreach ($skus as $key => $sku) {
            foreach ($gpubsSkus as $gpubsSku) {
                if ($sku['id'] == $gpubsSku['product_sku_id']) {
                    $sku['price'] = $gpubsSku['price'];
                    $sku['stock'] = $gpubsSku['stock'];
                    break;
                }
            }
            $allSkus[$key] = $sku;
        }
        $skuId = array_column($allSkus, 'sku_cartesian');
        $skuData = array_map(function($data) {
            unset($data['sku_cartesian']);
            return $data;
        }, $allSkus);
        return array_combine($skuId, $skuData);
    }

    public static function getProducts($currentPage, $pageSize)
    {
        $provider = new ActiveDataProvider([
            'query' => ActivityGpubsProductAR::find()->select([
                'id', 'product_id', 'status', 'hot_recommend', 'gpubs_type', 'gpubs_rule_type',
                'min_member_per_group', 'min_quantity_per_group', 'min_quantity_per_member_of_group',
            ]),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
        ]);

        $data = array_map(function ($obj) {
            $product = new Product(['id' => $obj->product_id]);
            $res = Handler::getMultiAttributes($product, [
                'mainImage',
                'title',
                '_func' => [
                    'mainImage' => function ($mainImage) {
                        return $mainImage->path;
                    }
                ]
            ]);

            $res['gpubsProductId'] = $obj->id;
            $res['product_id'] = $obj->product_id;
            $res['status'] = $obj->status;
            $res['hot_recomment'] = $obj->hot_recommend;
            $res['gpubs_type'] = $obj->gpubs_type;
            $res['gpubs_rule_type'] = $obj->gpubs_rule_type;
            $res['min_member_per_group'] = $obj->min_member_per_group;
            $res['min_quantity_per_group'] = $obj->min_quantity_per_group;
            $res['min_quanlity_per_member_of_group'] = $obj->min_quantity_per_member_of_group;
            $res['stockCount'] = ActivityGpubsProductSkuAR::find()->where(['product_id' => $obj->product_id])->sum('stock');
            $minPrice = ActivityGpubsProductSkuAR::find()->select('MIN(price)')->where(['product_id' => $obj->product_id])->scalar();
            $maxPrice = ActivityGpubsProductSkuAR::find()->select('MAX(price)')->where(['product_id' => $obj->product_id])->scalar();
            if ($minPrice === $maxPrice) {
                $price = $minPrice;
            } else {
                $price = $minPrice . ' - ' . $maxPrice;
            }
            $res['price'] = $price;

            if ($res['gpubs_type'] == GpubsProduct::GPUBS_TYPE_INVITE) {
                unset($res['min_member_per_group']);
                unset($res['min_quanlity_per_member_of_group']);
            } elseif ($res['gpubs_type'] == GpubsProduct::GPUBS_TYPE_DELIVER) {
                switch ($res['gpubs_rule_type']) {
                    case GpubsProduct::STATUS_GPUBS_RULE_MEMBER :
                        unset($res['min_quantity_per_group']);
                        unset($res['min_quanlity_per_member_of_group']);
                        break;
                    case GpubsProduct::STATUS_GPUBS_RULE_NUMBER :
                        unset($res['min_member_per_group']);
                        unset($res['min_quanlity_per_member_of_group']);
                        break;
                    case GpubsProduct::STATUS_GPUBS_PRE_NUMBER :
                        unset($res['min_quantity_per_group']);
                        break;
                    default:
                        break;
                }
            }
            return $res;
        }, $provider->models);
        return [
            'data' => $data,
            'count' => $provider->count,
            'totalCount' => $provider->totalCount,
        ];
    }

    public static function getProduct($product_id)
    {
        try {
            $gpubsProduct = ActivityGpubsProductAR::find()->select(['product_id', 'min_quantity_per_group', 'status'])->asArray()->one();
            $product = new Product(['id' => $product_id]);
            $res = Handler::getMultiAttributes($product, [
                'mainImage',
                'title',
                '_func' => [
                    'mainImage' => function ($mainImage) {
                        return $mainImage->path;
                    }
                ]
            ]);
            $res['product_id'] = $product_id;
            $res['min_quantity_per_group'] = $gpubsProduct['min_quantity_per_group'];
            $res['status'] = $gpubsProduct['status'];
            $res['customer_limit'] = $product->getCustomerLimit();
            $res['stockCount'] = ActivityGpubsProductSkuAR::find()->where(['product_id' => $product_id])->sum('stock');
            $minPrice = ActivityGpubsProductSkuAR::find()->select('MIN(price)')->where(['product_id' => $product_id])->scalar();
            $maxPrice = ActivityGpubsProductSkuAR::find()->select('MAX(price)')->where(['product_id' => $product_id])->scalar();
            if ($minPrice === $maxPrice) {
                $price = $minPrice;
            } else {
                $price = $minPrice . ' - ' . $maxPrice;
            }
            $res['price'] = $price;

            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function provideGpubsDetailList($currentPage, $pageSize, $where)
    {
        if (!$currentPage = (int)$currentPage)$currentPage = 1;
        if (!$pageSize = (int)$pageSize)$pageSize = 1;
        return new ActiveDataProvider([
            'query' => ActivityGpubsGroupDetailAR::find()->select([
                'id',
                'detail_number',
                'activity_gpubs_product_id',
                'activity_gpubs_group_id',
                'activity_gpubs_product_sku_id',
                'product_id',
                'product_sku_id',
                'product_title',
                'product_image_filename',
                'comment',
                'sku_attributes',
                'quantity',
                'custom_user_id',
                'own_user_id',
            ])->where($where)->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);
    }

    public static function getGpubsProduct($gpubs_type, $condition,$currentPage, $pageSize)
    {
        if ($gpubs_type == self::GPUBS_TYPE_ALL) {
            $productIds = ActivityGpubsProductAR::find()->select(['product_id'])->column();
        } else {
            $productIds = ActivityGpubsProductAR::find()->select(['product_id'])->where(['gpubs_type' => $gpubs_type])->column();
        }
        $searchProductIds = new ActiveDataProvider([
            'query' => ProductAR::find()
               ->select(['id'])
                ->where(['like', 'title', $condition])
                ->orWhere(['id' => intval($condition)])
                ->andWhere(['in', 'id', $productIds]),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
        ]);
        $datas = [];
        foreach ($searchProductIds->models as $searchProductId) {
            $provider = new ActiveDataProvider([
                'query' => ActivityGpubsProductAR::find()
                    ->select(['id', 'product_id', 'status', 'hot_recommend', 'gpubs_type', 'gpubs_rule_type',
                    'min_member_per_group', 'min_quantity_per_group', 'min_quantity_per_member_of_group',])
                    ->where(['product_id' => $searchProductId->id]),
            ]);

            $datas[] = current(array_map(function ($obj) {
                $product = new Product(['id' => $obj->product_id]);
                $res = Handler::getMultiAttributes($product, [
                    'mainImage',
                    'title',
                    '_func' => [
                        'mainImage' => function ($mainImage) {
                            return $mainImage->path;
                        }
                    ]
                ]);

                $res['gpubsProductId'] = $obj->id;
                $res['product_id'] = $obj->product_id;
                $res['min_quantity_per_group'] = $obj->min_quantity_per_group;
                $res['status'] = $obj->status;
                $res['hot_recomment'] = $obj->hot_recommend;
                $res['gpubs_type'] = $obj->gpubs_type;
                $res['gpubs_rule_type'] = $obj->gpubs_rule_type;
                $res['min_member_per_group'] = $obj->min_member_per_group;
                $res['min_quanlity_per_member_of_group'] = $obj->min_quantity_per_member_of_group;
                $res['stockCount'] = ActivityGpubsProductSkuAR::find()->where(['product_id' => $obj->product_id])->sum('stock');
                $minPrice = ActivityGpubsProductSkuAR::find()->select('MIN(price)')->where(['product_id' => $obj->product_id])->scalar();
                $maxPrice = ActivityGpubsProductSkuAR::find()->select('MAX(price)')->where(['product_id' => $obj->product_id])->scalar();
                if ($minPrice === $maxPrice) {
                    $price = $minPrice;
                } else {
                    $price = $minPrice . ' - ' . $maxPrice;
                }
                $res['price'] = $price;

                if ($res['gpubs_type'] == GpubsProduct::GPUBS_TYPE_INVITE) {
                    unset($res['min_member_per_group']);
                    unset($res['min_quanlity_per_member_of_group']);
                } elseif ($res['gpubs_type'] == GpubsProduct::GPUBS_TYPE_DELIVER) {
                    switch ($res['gpubs_rule_type']) {
                        case GpubsProduct::STATUS_GPUBS_RULE_MEMBER :
                            unset($res['min_quantity_per_group']);
                            unset($res['min_quanlity_per_member_of_group']);
                            break;
                        case GpubsProduct::STATUS_GPUBS_RULE_NUMBER :
                            unset($res['min_member_per_group']);
                            unset($res['min_quanlity_per_member_of_group']);
                            break;
                        case GpubsProduct::STATUS_GPUBS_PRE_NUMBER :
                            unset($res['min_quantity_per_group']);
                            break;
                        default:
                            break;
                    }
                }
                return $res;
            }, $provider->models));
        }
        return [
            'data' => $datas,
            'count' => $searchProductIds->count,
            'totalCount' => $searchProductIds->totalCount,
        ];
    }

}
