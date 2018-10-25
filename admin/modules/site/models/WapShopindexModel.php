<?php
/**
 * Created by PhpStorm.
 * User: forrest
 * Date: 28/05/18
 * Time: 18:18
 */

namespace admin\modules\site\models;

use common\ActiveRecord\BrandShopAdvAR;
use common\ActiveRecord\BrandShopRecommendAR;
use common\ActiveRecord\ProductAR;
use common\ActiveRecord\SupplyUserAR;
use common\models\Model;
use common\models\parts\brand\ShopAdv;
use common\models\parts\OSSImage;
use common\models\parts\Product;
use common\models\parts\supply\SupplyShop;
use supply\components\SupplyUser;
use Yii;
use yii\db\Exception;

class WapShopindexModel extends Model
{
    const SCE_CREATE = 'create';
    const SCE_LIST = 'get_list';
    const SCE_EDIT = 'edit';
    const SCE_DELETE = 'delete';
    const SCE_SORT = 'sort';
    const SCE_SUPPLY_SHOP_PRODUCTS = 'get_supply_shop_products';
    const SCE_SEARCH_SHOP_PRODUCT = 'search_supply_shop_product';
    const SCE_CREATE_PRODUCT = 'create_product';
    const SCE_LIST_PRODUCT = 'get_list_product';
    const SCE_EDIT_PRODUCT = 'edit_product';
    const SCE_DELETE_PRODUCT = 'delete_product';

    public $id;
    public $supply_user_id;
    public $file_name;
    public $image_url;
    public $type;
    public $sort;
    public $sort_items;
    public $show_title;
    public $show_message;
    public $product_id;
    public $recommend_product_id;
    public $condition;

    //轮播图排序sort值的取值范围 1 - 100
    private $_sort_range = 100;

    //wap图片类型取值范围 type = {3, 4}
    private $_adv_type = [
        ShopAdv::TYPE_WAP_BIG_IMG,
        ShopAdv::TYPE_WAP_CAROUSEL_IMG,
    ];

    const CAROUSEL_LIMIT = 5;

    public function scenarios()
    {
        return [
            self::SCE_CREATE => ['supply_user_id', 'image_url', 'file_name', 'type', 'sort'],
            self::SCE_LIST => ['supply_user_id'],
            self::SCE_EDIT => ['id', 'supply_user_id', 'image_url', 'file_name', 'sort'],
            self::SCE_DELETE => ['id'],
            self::SCE_SORT => ['sort_items'],
            self::SCE_SUPPLY_SHOP_PRODUCTS => ['supply_user_id'],
            self::SCE_SEARCH_SHOP_PRODUCT => ['condition', 'supply_user_id'],
            self::SCE_CREATE_PRODUCT => ['supply_user_id', 'product_id'],
            self::SCE_LIST_PRODUCT => ['supply_user_id'],
            self::SCE_EDIT_PRODUCT => ['id', 'show_title', 'show_message', 'file_name'],
            self::SCE_DELETE_PRODUCT => ['recommend_product_id'],
        ];
    }

    public function rules()
    {
        return [
            [['id', 'image_url', 'file_name', 'type', 'supply_user_id', 'sort', 'sort_items', 'product_id', 'recommend_product_id', 'show_title', 'show_message'], 'required', 'message' => 9001],
            [['supply_user_id'], 'exist', 'targetClass' => SupplyUserAR::className(), 'targetAttribute' => 'id', 'message' => 5216],
            [['id'], 'integer', 'message' => 5097],
            [
                ['type'],
                'in',
                'range' => $this->_adv_type,
                'message' => 5491,
            ],
            [
                ['image_url'],
                'url',
                'message' => 5265,
            ],
            [
                ['sort'],
                'in',
                'range' => range(1, $this->_sort_range),
                'message' => 5492,
            ],
//            [
//                ['sort'],
//                'unique',
//                'targetClass' => BrandShopAdvAR::className(),
//                'targetAttribute' => ['supply_user_id' => 'supply_user_id', 'type' => 'type', 'sort' => 'sort'],
//                'message' => 5258,
//            ],
            [
                ['id'],
                'exist',
                'targetClass' => BrandShopAdvAR::className(),
                'targetAttribute' => ['id' => 'id'],
                'message' => 5219,
            ],
            [
                ['show_title'],
                'string',
                'length' => [1, 255],
                'tooShort' => 5483,
                'tooLong' => 5484,
                'message' => 5099,
            ],
            [
                ['show_message'],
                'string',
                'length' => [1, 255],
                'tooShort' => 5485,
                'tooLong' => 5486,
                'message' => 5099,
            ],
            [
                ['product_id'],
                'exist',
                'targetClass' => ProductAR::className(),
                'targetAttribute' => ['product_id' => 'id', 'supply_user_id' => 'supply_user_id'],
                'message' => 5495,
            ],
            [
                ['product_id'],
                'unique',
                'targetClass' => BrandShopRecommendAR::className(),
                'targetAttribute' => ['product_id' => 'product_id', 'supply_user_id' => 'supply_user_id'],
                'message' => 5497,
            ],
//            [
//                ['condition'],
//                'string',
//                'length' => [0, 255],
//                'tooShort' => 5498,
//                'tooLong' => 5498,
//                'message' => 5498,
//            ]
        ];
    }

    // 创建主图或轮播图
    public function create()
    {
        if ($this->type == 4 && BrandShopAdvAR::find()->where(['supply_user_id' => $this->supply_user_id, 'sort' => $this->sort, 'type' => ShopAdv::TYPE_WAP_CAROUSEL_IMG])->exists()) {
            $this->addError('create',5494);
            return false;
        }
        if ($this->type == 4 && BrandShopAdvAR::find()->where(['supply_user_id' => $this->supply_user_id, 'type'=> ShopAdv::TYPE_WAP_CAROUSEL_IMG])->count() >= self::CAROUSEL_LIMIT) {
            $this->addError('create',5499);
            return false;
        }
        $supplyUser = new SupplyShop(['id' => $this->supply_user_id]);
        if ($supplyUser->createWap($this->file_name, current($this->getImages()->getPath()), $this->image_url, $this->type, $this->sort)) {
            return true;
        }
        $this->addError('create', 5214);
        return false;
    }

    // 获取主图和轮播图
    public function getList()
    {
        return (new SupplyShop(['id' => $this->supply_user_id]))->getWapShopAdv(range(1, $this->_sort_range));
    }

    // 编辑轮播图
    public function edit()
    {
        if (BrandShopAdvAR::find()->where(['supply_user_id' => $this->supply_user_id, 'sort' => $this->sort, 'type' => ShopAdv::TYPE_WAP_CAROUSEL_IMG])
            ->andWhere(['<>', 'id', $this->id])->exists()) {
            $this->addError('edit',5494);
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ((new ShopAdv(['id' => $this->id]))->setMulti($this->file_name, current($this->getImages()->getPath()), $this->image_url) === false){
                throw new \Exception();
            }
            $model = BrandShopAdvAR::findOne($this->id);
            $model->sort = $this->sort;
            $model->image_url = $this->image_url;
            $model->file_name = $this->file_name;
            if (!$model->save()) {
                throw new \Exception();
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $this->addError('edit',5215);
            $transaction->rollBack();
            return false;
        }
    }

    // 删除主图或轮播图
    public function delete()
    {
        if ($adv = BrandShopAdvAR::findOne($this->id)) {
            $adv->delete();
            return true;
        } else {
            $this->addError('delete', 5092);
            return false;
        }
    }

    // 轮播图排序
    public function sort()
    {
        if (!is_array($this->sort_items)) {
            $this->addError('sort_items', 9002);
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($this->sort_items as $sort => $id) {
                BrandShopAdvAR::updateAll(['sort' => ++$sort], ['id' => $id]);
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->addError('sort_items', 9002);
            return false;
        }
    }

    // 获取店铺商品
    public function getSupplyShopProducts()
    {
        if ($product = (new SupplyShop(['id' => $this->supply_user_id]))->getWapProduct()) {
            return $product;
        } else {
            // $this->addError('getSupplyShopProducts', 5486);
            // return false;
            return [];
        }
    }

    // 查找店铺商品
    public function searchSupplyShopProduct()
    {
        if (empty($this->condition)) {
            return $this->getSupplyShopProducts();
        }
        if ($product = (new SupplyShop(['id' => $this->supply_user_id]))->SearchProduct($this->condition)) {
            return $product;
        } else {
            return [];
        }
    }

    // 添加甄选商品
    public function createProduct()
    {
//        $product = ProductAR::find()->where(['id' => $this->product_id])->asArray()->one();
//        if ($product) {
//            $title = $product['title'];
//            $description = $product['description'];
//        } else {
//            $title = '';
//            $description = '';
//        }
        $title = '';
        $description = '';
        $supplyUser = new SupplyShop(['id' => $this->supply_user_id]);
        if ($id = $supplyUser->createShopRecommend($this->file_name = '', '', $this->product_id, $title, $description)) {
            return ['id' => $id];
        }
        $this->addError('create', 5487);
        return false;
    }

    // 显示甄选商品
    public function getListProduct()
    {
        if ($products = (new SupplyShop(['id' => $this->supply_user_id]))->getWapShopRecommend($this->supply_user_id)) {
            return $products;
        }
        return [];
    }

    // 编辑甄选商品
    public function editProduct()
    {
        $recommend = BrandShopRecommendAR::findOne($this->id);
        if (!$recommend) {
            $this->addError('editProduct', 5495);
            return false;
        }
        if (!$this->getImages()) {
            $this->addError('editProduct', 5496);
            return false;
        }

        $recommend->file_name = $this->file_name;
        $recommend->image_path = current($this->getImages()->getPath());
        $recommend->show_title = $this->show_title;
        $recommend->show_message = $this->show_message;
        if ($recommend->save()) {
            return true;
        } else {
            $this->addError('editProduct', 5489);
            return false;
        }
    }

    // 删除甄选商品
    public function deleteProduct()
    {
        if ($recommend = BrandShopRecommendAR::findOne($this->recommend_product_id)) {
            $recommend->delete();
            return true;
        } else {
            $this->addError('deleteProduct', 5490);
            return false;
        }
    }

    // 获取oos文件对象
    private function getImages()
    {
        try {
            if ($oss = new OSSImage(['images' => ['filename' => $this->file_name]])) {
                return $oss;
            } else {
                throw new \Exception();
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    // 前台显示甄选商品
    public function getMobileListProduct()
    {
        if ($products = (new SupplyShop(['id' => $this->supply_user_id]))->getMobileShopRecommend($this->supply_user_id)) {
            return $products;
        }
        return [];
    }
}
