<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/5/8
 * Time: 下午3:30
 */

namespace common\models\parts\supply;
use common\ActiveRecord\BrandShopAdvAR;
use common\ActiveRecord\BrandShopRecommendAR;
use common\ActiveRecord\ProductAR;
use common\ActiveRecord\SupplyUserAR;
use common\components\handler\Handler;
use common\models\parts\brand\ShopAdv;
use common\models\parts\Product;
use custom\models\parts\temp\OrderLimit\ProductLimit;
use Yii;
use yii\data\ActiveDataProvider;

class SupplyShop extends SupplyUser
{
    public function getMobileShopRecommend($supply_user_id)
    {
        $recommends = BrandShopRecommendAR::find()->select(['id', 'file_name', 'image_path', 'product_id', 'show_title', 'show_message'])
            ->where(['supply_user_id' => $supply_user_id])
            ->asArray()->all();
        $datas = [];
        foreach ($recommends as $key => $recommend) {
            $product = ProductAR::find()->select(['title', 'description', 'min_price', 'max_price'])->where(['id' => $recommend['product_id']])->asArray()->one();
            if (!is_array($product)) {
                unset($product);
                continue;
            }
            $datas[$key] = array_merge($recommend, $product);
            $datas[$key]['show_title'] = empty($datas[$key]['show_title']) ? $datas[$key]['title'] : $datas[$key]['show_title'];
            $datas[$key]['show_message'] = empty($datas[$key]['show_message']) ? $datas[$key]['description'] : $datas[$key]['show_message'];
            $supplyName = SupplyUserAR::find()->select(['brand_name'])->where(['id' => $this->id])->scalar();
            $datas[$key]['supply_name'] = $supplyName;
            unset($product);
            unset($supplyName);
        }
        $result = array_values($datas);
        return array_map(function ($product) {
            if ($product['file_name'] === '') {
                $oss = (new Product(['id' => $product['product_id']]))->mainImage;
                $product['file_name'] = $oss->name;
                $product['image_path'] = $oss->path;
                unset($oss);
            }
            return $product;
        }, $result);
    }


    public function getWapShopRecommend($supply_user_id)
    {
        $recommends = BrandShopRecommendAR::find()->select(['id', 'file_name', 'image_path', 'product_id', 'show_title', 'show_message'])
            ->where(['supply_user_id' => $supply_user_id])
            ->asArray()->all();
        $datas = [];
        foreach ($recommends as $key => $recommend) {
            $product = ProductAR::find()->select(['title', 'description', 'min_price', 'max_price'])->where(['id' => $recommend['product_id']])->asArray()->one();
            if (!is_array($product)) {
                unset($product);
                continue;
            }
            $datas[$key] = array_merge($recommend, $product);
            $supplyName = SupplyUserAR::find()->select(['brand_name'])->where(['id' => $this->id])->scalar();
            $datas[$key]['supply_name'] = $supplyName;
            unset($product);
            unset($supplyName);
        }
        return array_values($datas);
    }

    // 创建一条甄选商品推荐记录
    public function createShopRecommend($fileName, $imagePath, $productId, $title, $description)
    {
        return  Yii::$app->RQ->AR(new BrandShopRecommendAR())->insert([
            'supply_user_id' => $this->id,
            'file_name' => $fileName,
            'image_path' => $imagePath,
            'product_id' => $productId,
            'show_title' => $title,
            'show_message' => $description,
        ]);
    }

    // 获取wap版广告位信息
    public function getWapShopAdv($sorts)
    {
        $carousels = [];
        foreach ($sorts as $key => $sort) {
            if ($this->getAdvInfo(ShopAdv::TYPE_WAP_CAROUSEL_IMG, $sort)['id'] != '') {
                $arr = $this->getAdvInfo(ShopAdv::TYPE_WAP_CAROUSEL_IMG, $sort);
                $arr['sort'] = $sort;
                $carousels[] = $arr;
                unset($arr);
            }
        }
        return [
            'big' => $this->getAdvInfo(ShopAdv::TYPE_WAP_BIG_IMG, 1),
            'carousel' => $carousels,
        ];
    }

    // 查找商品
    public function searchProduct($condition)
    {
        $limitProductId = ProductLimit::getLimitProductId();
        $productIds = ProductAR::find()->select(['id'])
            ->where(['supply_user_id' => $this->id, 'sale_status' => Product::SALE_STATUS_ONSALE])
            ->andWhere(['not in', 'id', $limitProductId])
            ->column();

        if (is_numeric($condition)) {
            if (!in_array($condition, $productIds)) {
                return false;
            }
            $product = new Product(['id' => $condition]);
            $data = [];
            $data[] = Handler::getMultiAttributes($product, [
                'id', 'title', 'price', 'supplier', 'supplier', 'mainImage',
                'title', 'description', 'price',
                '_func' => [
                    'supplier' => function ($supplier) {
                        return SupplyUserAR::find()->select(['company_name'])->where(['id' => $supplier])->scalar() ?? '';
                    },
                    'mainImage' => function ($mainImage) {
                        return $mainImage->name ?? '';
                    },
                ],
            ]);
            $data[0]['image_path'] = Yii::$app->params['OSS_PostHost'] . $data[0]['mainImage'];
            return $data;
        } else {
            $ids = ProductAR::find()->select(['id'])->where(['like', 'title', $condition])->andWhere(['not in', 'id', $productIds])->column();
            $products = [];
            foreach ($ids as $key => $id) {
                $product = new Product(['id' => $id]);
                $products[$key] = Handler::getMultiAttributes($product, [
                    'id', 'title', 'price', 'supplier', 'supplier', 'mainImage',
                    'title', 'description', 'price',
                    '_func' => [
                        'supplier' => function ($supplier) {
                            return SupplyUserAR::find()->select(['company_name'])->where(['id' => $supplier])->scalar() ?? '';
                        },
                        'mainImage' => function ($mainImage) {
                            return $mainImage->name ?? '';
                        },
                    ],
                ]);
                $products[$key]['image_path'] = Yii::$app->params['OSS_PostHost'] . $products[$key]['mainImage'];
            }
            return $products;
        }
    }

    // 获取wap版店铺商品
    public function getWapProduct()
    {
        $limitProductId = ProductLimit::getLimitProductId();
        $productIds = ProductAR::find()->select(['id'])
            ->where(['supply_user_id' => $this->id, 'sale_status' => Product::SALE_STATUS_ONSALE])
            ->andWhere(['not in', 'id', $limitProductId])
            ->column();
        $products = [];
        foreach ($productIds as $key => $productId) {
            $product = new Product(['id' => $productId]);
            $products[$key] = Handler::getMultiAttributes($product, [
                'id', 'title', 'price', 'supplier', 'supplier', 'mainImage',
                'title', 'description', 'price',
                '_func' => [
                    'supplier' => function ($supplier) {
                        return SupplyUserAR::find()->select(['company_name'])->where(['id' => $supplier])->scalar() ?? '';
                    },
                    'mainImage' => function ($mainImage) {
                        return $mainImage->name ?? '';
                    },
                ],
            ]);
            $products[$key]['image_path'] = Yii::$app->params['OSS_PostHost'] . $products[$key]['mainImage'];
        }
        return $products;
    }


    public function createWap($fileName, $imagePath, $imageUrl, $type, $sort)
    {
        if ($adv = BrandShopAdvAR::find()->where(['supply_user_id' => $this->id, 'type' => $type, 'sort' => $sort])->one()) {
            $adv->supply_user_id = $this->id;
            $adv->file_name = $fileName;
            $adv->image_path = $imagePath;
            $adv->image_url = $imageUrl;
            $adv->type = $type;
            $adv->sort = $sort;
            if ($adv->save()) {
                return true;
            } else {
                return false;
            }
        }
        return $this->createShop($fileName, $imagePath, $imageUrl, $type, $sort);
    }

    public function createShop($fileName,$imagePath,$imageUrl,$type,$sort,$return='false'){
       return  Yii::$app->RQ->AR(new BrandShopAdvAR())->insert([
           'supply_user_id'=>$this->id,
           'file_name'=>$fileName,
           'image_path'=>$imagePath,
           'image_url'=>$imageUrl,
           'type'=>$type,
           'sort'=>$sort,
       ],$return);
    }

    public function getShopAdv(){

        return [
            'top'=>$this->getAdvInfo(ShopAdv::TYPE_BIG_IMG,ShopAdv::SORT_TOP_ADV),
            'small'=>[
                0=>$this->getAdvInfo(ShopAdv::TYPE_SMALL_IMG,ShopAdv::SORT_HOT_FIRST_ADV),
                1=>$this->getAdvInfo(ShopAdv::TYPE_SMALL_IMG,ShopAdv::SORT_HOT_SECOND_ADV),
            ],
            'sub'=>[
                0=>$this->getAdvInfo(ShopAdv::TYPE_SUB_IMG,ShopAdv::SORT_SUB_FIRST_ADV),
                1=>$this->getAdvInfo(ShopAdv::TYPE_SUB_IMG,ShopAdv::SORT_SUB_SECOND_ADV),
                2=>$this->getAdvInfo(ShopAdv::TYPE_SUB_IMG,ShopAdv::SORT_SUB_THIRD_ADV),
            ],
        ];
    }



    //获取广告位信息
    private function getAdvInfo($type,$sort=0){
        $where="type='$type' and supply_user_id='$this->id' and sort='$sort'";
        $result=Yii::$app->RQ->AR(new BrandShopAdvAR())->column([
            'select'=>['id'],
            'where'=>$where,
            'orderBy'=>['sort'=>SORT_ASC],
        ]);

        if($result){
            $adv=new ShopAdv(['id'=>$result[0]]);
            return [
                'id'=>$adv->id,
                'file_name'=>$adv->getFileName(),
                'image_path'=>$adv->getImagePath(),
                'image_url'=>$adv->getImageUrl(),
            ];
        }
        return $this->getDefaultEmptyAdv();

    }

    //获取默认配置
    private function getDefaultEmptyAdv(){
        return [
            'id'=>'',
            'file_name'=>'',
            'image_path'=>'',
            'image_url'=>'',
        ];
    }

    /**
     *====================================================
     * 获取该供应商上架商品
     * @param int $status
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function getProduct($currentPage,$pageSize,$status = 1){
        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 10;
        //获取限制商品id
        $limitProductId = ProductLimit::getLimitProductId();
        $level = Yii::$app->user->isGuest ? 2 : Yii::$app->CustomUser->CurrentUser->level;
        return  new ActiveDataProvider([
            'query' => ProductAR::find()->select([
                'id',
            ])
                ->where(['sale_status'=>$status, 'supply_user_id'=>$this->id])
                ->andWhere(['not in','id',$limitProductId])
                ->andWhere(['<=','customer_limit',$level])
                ->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
        ]);
    }

    // 根据条件来获取供应商上架商品
    public function getProductByConditon($currentPage, $pageSize, $condition = 'sales', $order = 0, $status = 1)
    {
        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 10;
        $condition = ($condition === 'sales') ? 'sales' : 'min_price';
        $order = ($order === 0) ? 'DESC' : 'ASC';
        //获取限制商品id
        $limitProductId = ProductLimit::getLimitProductId();
        $level = Yii::$app->user->isGuest ? 2 : Yii::$app->CustomUser->CurrentUser->level;
        return new ActiveDataProvider([
            'query' => ProductAR::find()->select(['id'])
                ->where(['sale_status'=>$status, 'supply_user_id'=>$this->id])
                ->andWhere(['not in','id',$limitProductId])
                ->andWhere(['<=','customer_limit',$level])
                ->orderBy($condition .' '. $order)
                ->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
        ]);
    }

}