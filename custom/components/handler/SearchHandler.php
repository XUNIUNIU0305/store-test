<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/9/18
 * Time: 下午5:57
 */

namespace custom\components\handler;


use common\ActiveRecord\ProductAR;
use common\ActiveRecord\ProductSPUAR;
use common\components\handler\Handler;
use common\models\parts\Product;
use custom\models\parts\temp\OrderLimit\ProductLimit;
use Yii;
use yii\data\ActiveDataProvider;

class SearchHandler extends Handler
{
    public static function getProductIdByOptionId($endCategoryId, $optionId, $currentPage, $pageSize, $orderBy){

        if (empty($optionId))
        {
            $where = ['product_category_id' => $endCategoryId];
        }
        else
        {
            if (is_array($optionId))
            {
                $AR = Yii::$app->RQ->AR(new ProductSPUAR());
                $productId = [];
                foreach ($optionId as $ids)
                {
                    $someProductId = [];
                    if (is_array($ids))
                    {
                        foreach ($ids as $id)
                        {
                            $res = $AR->column([
                                'select' => 'product_id',
                                'where' => ['product_spu_option_id' => $id]
                            ]);
                            $someProductId = array_merge($someProductId, $res);
                        }
                        $productId[] = array_unique($someProductId);
                    }
                    else
                    {
                        $productId[] = $AR->column([
                            'select' => 'product_id',
                            'where' => ['product_spu_option_id' => $ids]
                        ]);
                    }
                }
                $productId = array_reduce($productId, function ($carry, $item){
                    return array_intersect($carry, $item);
                },$productId[0]);
                $where = ['id' => $productId];
            }
            else
            {
                throw new \Exception('option is not array');
            }
        }
        $defaultOrder = [];
        if ($orderBy){
            foreach ($orderBy as $name=>$sort){
                $defaultOrder[$name] = ($sort == 'desc' ) ? SORT_DESC : SORT_ASC;
            }
        }else{
            $defaultOrder['id'] = SORT_DESC;
        }
        //获取当前用户登录等级
        $level =  Yii::$app->user->isGuest ? 2 : Yii::$app->CustomUser->CurrentUser->level;

        //获取限制商品id
        $limitProductId = ProductLimit::getLimitProductId();

        return new ActiveDataProvider([
            'query' => ProductAR::find()->select(['id'])->where($where)
                ->andWhere(['sale_status'=>Product::SALE_STATUS_ONSALE,])
                ->andWhere(['<=','customer_limit',$level])
                ->andWhere(['not in','id',$limitProductId])
                ->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => $defaultOrder,
            ],
        ]);
    }



}