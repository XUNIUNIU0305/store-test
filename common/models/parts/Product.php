<?php
namespace common\models\parts;

use Yii;
use yii\base\Object;
use common\models\RapidQuery;
use common\ActiveRecord\ProductAR;
use common\ActiveRecord\ProductSPUAR;
use yii\base\InvalidConfigException;
use common\models\parts\ProductSPU;
use common\ActiveRecord\ProductBigImagesAR;
use common\models\parts\OSSImage;
use common\models\parts\ProductImage;
use common\ActiveRecord\ProductSKUAR;
use common\ActiveRecord\ProductSKUAttributeAR;
use common\ActiveRecord\ProductSKUOptionAR;
use common\models\parts\ProductSKU;
use common\ActiveRecord\OSSUploadFileAR;

/**
 * [attributes]:
 * category 所属分类
 * supplier 供应商
 * title 标题
 * description 描述
 * purchaseLocation 采购地
 * invoice 发票
 * warranty 保修
 * mainImage 主图
 * bigImages 大图
 * detail 详情
 * SPU 关键属性
 * SKU 销售属性
 * price 售价
 * saleStatus 当前商品销售状态
 * saleStatuses 全部销售状态
 * sales 销量
 */
class Product extends Object{

    //标题最大长度
    const TITLE_MAX_LENGTH = 30;
    //描述最大长度
    const DESCRIPTION_MAX_LENGTH = 150;
    //大图最少张数
    const BIG_IMG_MIN_COUNT = 4;
    //大图最多张数
    const BIG_IMG_MAX_COUNT = 5;
    //采购地：国内 | 国外
    const PURCHASE_INLAND = 0;
    const PURCHASE_OUTLAND = 1;
    //发票：有 | 无
    const HAS_INVOICE = 1;
    const NO_INVOICE = 0;
    //保修：有 | 无
    const HAS_WARRANTY = 1;
    const NO_WARRANTY = 0;
    //销售状态：未完成 | 在售 | 未售
    const SALE_STATUS_INCOMPLETE = 0;
    const SALE_STATUS_ONSALE = 1;
    const SALE_STATUS_UNSOLD = 2;

    const TYPE_STANDARD = 0;
    const TYPE_CUSTOMIZATION = 1;
    //用户购买限制
    const TYPE_REPUBLICS = 2;
    const TYPE_SYSTEM = 3;
    const TYPE_SUPPLY = 4;

    //主键
    public $id;

    //ActiveRecord对象
    protected $product;

    /**
     * 初始化对象
     * 检查商品主键是否存在
     */
    public function init(){
        if(is_null($this->id))throw new InvalidConfigException;
        if(!$this->product = ProductAR::findOne($this->id))throw new InvalidConfigException;
    }

    /**
     * 获取所属分类
     *
     * @return integer
     */
    public function getCategory(){
        return $this->product->product_category_id;
    }

    /**
     * 获取供应商ID
     *
     * @return integer
     */
    public function getSupplier(){
        return $this->product->supply_user_id;
    }

    public function getSupplierObj(){
        return new Supplier([
            'id' => $this->product->supply_user_id,
        ]);
    }

    /**
     * 获取标题
     *
     * @return string
     */
    public function getTitle(){
        return $this->product->title;
    }

    public function setTitle(string $title, $return = 'throw'){
        if($title == '')return Yii::$app->EC->callback($return, 'string');
        return Yii::$app->RQ->AR($this->product)->update([
            'title' => $title,
        ], $return);
    }

    /**
     * 获取描述
     *
     * @return string
     */
    public function getDescription(){
        return $this->product->description;
    }

    public function setDescription(string $description, $return = 'throw'){
        if($description == '')return Yii::$app->EC->callback($return, 'string');
        return Yii::$app->RQ->AR($this->product)->update([
            'description' => $description,
        ], $return);
    }

    /**
     * 获取采购地
     *
     * @return integer
     */
    public function getPurchaseLocation(){
        return $this->product->purchase_location;
    }

    /**
     * 获取发票
     *
     * @return integer
     */
    public function getInvoice(){
        return $this->product->invoice;
    }

    /**
     * 获取保修
     *
     * @return integer
     */
    public function getWarranty(){
        return $this->product->warranty;
    }

    /**
     * 获取定制
     *
     * @return integer
     */
    public function getCustomization(){
        return $this->product->customization;
    }

    /**
     * 可购买用户等级
     *
     * @return integer
     */
    public function getCustomerLimit(){
        return $this->product->customer_limit;
    }

    /**
     * 获取价格
     *
     * @return array
     */
    public function getPrice(){
        return [
            'min' => $this->product->min_price,
            'max' => $this->product->max_price,
        ];
    }

    public function getGuidancePrice(){
        return ProductSKUAR::find()->select(['min' => 'MIN(`guidance_price`)', 'max' => 'MAX(`guidance_price`)'])->where(['product_id' => $this->id])->asArray()->one();
    }

    /**
     * 获取详情
     *
     * @return string
     */
    public function getDetail(){
        return $this->product->detail;
    }

    public function setDetail(string $detail, $return = 'throw'){
        if($detail == '')return Yii::$app->EC->callback($return, 'string');
        return Yii::$app->RQ->AR($this->product)->update([
            'detail' => $detail,
        ], $return);
    }

    public function getMobileDetail(){
        return $this->product->mobile_detail;
    }

    public function setMobileDetail(string $detail, $return = 'throw'){
        return Yii::$app->RQ->AR($this->product)->update([
            'mobile_detail' => $detail,
        ]);
    }

    /**
     * 获取销量
     *
     * @return integer
     */
    public function getSales(){
        return $this->product->sales;
    }

    /**
     * 获取已付款数量
     *
     * @return integer
     */
    public function getPaid(){
        return $this->product->paid;
    }

    /**
     * 设置价格
     *
     * @param integer|array $price
     *
     * @return boolean
     */
    public function setPrice($price){
        if(is_array($price)){
            $newPrice = [
                'min_price' => $price[0],
                'max_price' => $price[1],
            ];
        }else{
            $newPrice = [
                'min_price' => $price,
                'max_price' => $price,
            ];
        }
        $this->product->min_price = $newPrice['min_price'];
        $this->product->max_price = $newPrice['max_price'];
        return $this->product->update();
    }

    /**
     * 设置商品大图
     *
     * @param $images OSSImage对象
     * @param $sorted 是否排序 true时根据OSSImage对象的顺序从1开始排序
     *
     * @return boolean
     */
    public function setBigImages(OSSImage $images, $sorted = false){
        $originImagesId = $this->bigImages->has() ? $this->bigImages->getId() : [];
        $modifiedImagesId = $images->getId();
        $addImagesId = array_diff($modifiedImagesId, $originImagesId);
        $removeImagesId = array_diff($originImagesId, $modifiedImagesId);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            !$addImagesId or $this->addBigImages($addImagesId);
            !$removeImagesId or $this->removeBigImages($removeImagesId);
            if($sorted){
                $this->sortBigImages($modifiedImagesId);
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 设置商品主图
     *
     * 当商品已有主图时会先执行self::setBigImages()进行排序，再设置主图
     *
     * @param $image OSSImage对象 需要设置成主图的对象必须已是该商品的大图
     *
     * @return boolean
     */
    public function setMainImage(OSSImage $image){
        $imageId = $image->getId();
        is_array($imageId) && $imageId = current($imageId);
        if(!in_array($imageId, $this->bigImages->getId()))return false;
        $hasMainImage = (new RapidQuery(new ProductBigImagesAR))->exists([
            'where' => [
                'product_id' => $this->id,
                'sort' => ProductBigImagesAR::MAIN_IMAGE_SORT,
            ],
        ]);
        if($hasMainImage){
            if(!$this->setBigImages($this->bigImages, true))return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            (new RapidQuery(ProductBigImagesAR::findOne([
                'product_id' => $this->id,
                'oss_upload_file_id' => $imageId,
            ])))->update([
                'sort' => ProductBigImagesAR::MAIN_IMAGE_SORT,
            ]);
            $bigImageId = (new RapidQuery(new ProductBigImagesAR))->scalar([
                'select' => ['id'],
                'where' => [
                    'product_id' => $this->id,
                    'oss_upload_file_id' => $imageId,
                ],
            ]);
            $this->product->product_big_images_id = $bigImageId;
            $this->product->update();
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 绑定多个商品属性
     *
     * @return boolean
     */
    public function bindSPUs($attributes){
        if(is_array($attributes)){
            foreach($attributes as $attribute){
                if(!$this->bindSPU($attribute))return false;
            }
            return true;
        }else{
            return $this->bindSPU($attributes);
        }
    }

    /**
     * 绑定单个商品属性
     *
     * @param $attribute Attribute对象
     *
     * @return boolean
     */
    public function bindSPU(Attribute $attribute){
        if(is_null($attribute->selectedOption))return false;
        return (new RapidQuery(new ProductSPUAR))->insert([
            'product_id' => $this->id,
            'product_spu_attribute_id' => $attribute->id,
            'product_spu_option_id' => $attribute->selectedOption,
        ]);
    }

    /**
     * 设置销售属性
     *
     * 设置成功后更新商品价格
     *
     * @param array $attrs 属性和选项
     * [
     *     [attr_name1 => [opt_name1_1, opt_name1_2]],
     *     [attr_name2 => [opt_name2_1, opt_name2_2]],
     * ]
     * @param array $cartesian [ 笛卡尔积组合 => 价格、数量等设置]
     * [
     *     [sku_id1 => ['price' => 1, 'stock' => 1, ...]],
     *     [sku_id2 => ['price' => 2, 'stock' => 2, ...]],
     * ]
     *
     * @return boolean
     */
    public function setSKU($attrs, $cartesian){
        $skuExist = (new RapidQuery(new ProductSKUAR))->exists([
            'select' => ['id'],
            'where' => ['product_id' => $this->id],
            'limit' => 1,
        ]);
        if($skuExist)return false;
        $skuAttributeExist = (new RapidQuery(new ProductSKUAttributeAR))->exists([
            'select' => ['id'],
            'where' => ['product_id' => $this->id],
            'limit' => 1,
        ]);
        if($skuAttributeExist)return false;
        $skuOptionExist = (new RapidQuery(new ProductSKUOptionAR))->exists([
            'select' => ['id'],
            'where' => ['product_id' => $this->id],
            'limit' => 1,
        ]);
        if($skuOptionExist)return false;
        $sku = new ProductSKUGenerator([
            'productId' => $this->id,
            'attrs' => $attrs,
            'cartesian' => $cartesian,
        ]);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(!$sku->generate())throw new \Exception;
            if(!$this->setPrice(array_values($this->SKU->getPriceinterval())))throw new \Exception;
            if(!$this->setSaleStatus(self::SALE_STATUS_UNSOLD))throw new \Exception;
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 获取商品的属性对象
     *
     * @return Object ProductSPU
     */
    public function getSPU(){
        return new ProductSPU(['productId' => $this->id]);
    }

    /**
     * 获取商品销售属性
     *
     * @return Object ProductSKU
     */
    public function getSKU(){
        return new ProductSKU(['productId' => $this->id]);
    }

    /**
     * 获取商品的大图对象
     *
     * @return Object ProductImage
     */
    public function getBigImages(){
        return new ProductImage(['productId' => $this->id]);
    }

    /**
     * 获取主图
     *
     * @return Object OSSImage
     */
    public function getMainImage(){
        return $this->bigImages->mainImage;
    }

    /**
     * 获取销售状态
     *
     * @return integer
     */
    public function getSaleStatus(){
        return $this->product->sale_status;
    }

    /**
     * 获取该商品是否在售
     *
     * @return boolean
     */
    public function getOnSale(){
        return $this->saleStatus == self::SALE_STATUS_ONSALE;
    }

    public function getIsCustomization(){
        return ($this->product->customization == self::TYPE_CUSTOMIZATION);
    }

    /**
     * 设置销售状态
     *
     * @return boolean
     */
    public function setSaleStatus($status){
        if(in_array($status, self::getSaleStatuses())){
            $this->product->sale_status = $status;
            return $this->product->update();
        }else{
            return false;
        }
    }

    /**
     * 增加销量
     *
     * @return boolean
     */
    public function increaseSales(int $count = 1){
        return $this->product->updateCounters(['sales' => $count]);
    }

    /**
     * 增加已付款数量
     *
     * @return boolean
     */
    public function increasePaid(int $count = 1){
        return $this->product->updateCounters(['paid' => $count]);
    }

    /**
     * 获取全部销售状态
     *
     * @return array
     */
    public static function getSaleStatuses(){
        return [
            self::SALE_STATUS_INCOMPLETE,
            self::SALE_STATUS_ONSALE,
            self::SALE_STATUS_UNSOLD,
        ];
    }

    /**
     * 对商品大图执行排序
     */
    protected function sortBigImages($imagesId){
        $i = ProductBigImagesAR::DEFAULT_SORT;
        foreach($imagesId as $id){
            (new RapidQuery(ProductBigImagesAR::findOne([
                'product_id' => $this->id,
                'oss_upload_file_id' => $id,
            ])))->update([
                'sort' => $i,
            ]);
            ++$i;
        }
    }

    /**
     * 删除大图
     *
     * @return boolean
     */
    protected function removeBigImages(array $imagesId, $return = 'throw'){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($imagesId as $imageId){
                if(ProductBigImagesAR::findOne([
                    'product_id' => $this->id,
                    'oss_upload_file_id' => $imageId,
                ])->delete() === false)throw new \Exception;
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, 'mysql');
        }
    }

    /**
     * 添加大图
     *
     * @return boolean
     */
    protected function addBigImages($imagesId){
        $images = new OSSImage(['images' => ['id' => $imagesId]]);
        $imagesName = $images->getName();
        $imagesId = $images->getId();
        foreach($imagesName as $key => $name){
            $batchImages[] = [
                $this->id,
                $imagesId[$key],
                $name,
                ProductBigImagesAR::DEFAULT_SORT,
            ];
        }
        return Yii::$app->db->createCommand()->batchInsert(ProductBigImagesAR::tableName(), ['product_id', 'oss_upload_file_id', 'filename', 'sort'], $batchImages)->execute();
    }


    /**
     *====================================================
     * 设置商品搜索关键字
     * @param $keyword
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function setKeyword($keyword){
        $this->product->keyword = $keyword;
        return $this->product->update();
    }

    /**
     *====================================================
     * 获取搜索关键字
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function getKeyword(){
        return $this->product->keyword;
    }


    /**
     *====================================================
     * 获取商品所属分类对象
     * @return ProductCategory
     * @author shuang.li
     *====================================================
     */
    public function getCategoryObj(){
        return new ProductCategory(['id'=>$this->product->product_category_id]);
    }


    public function getOriginalPrice()
    {
        $originalPrices = ProductSKUAR::find()->select(['original_price'])->where(['product_id' => $this->id])->asArray()->column();
        $originalPrices = array_map(function ($price) {
            return doubleval($price);
        }, $originalPrices);
        foreach ($originalPrices as $key => $originalPrice) {
            if (empty($originalPrice)) {
                unset($originalPrices[$key]);
            }
        }
        return [
            'min' => $originalPrices ? min($originalPrices) : 0,
            'max' => $originalPrices ? max($originalPrices) : 0,
        ];
    }

    public function getOriginalGuidancePrice()
    {
        $originalGuidancePrices = ProductSKUAR::find()->select(['original_guidance_price'])->where(['product_id' => $this->id])->asArray()->column();
        $originalGuidancePrices = array_map(function ($price) {
            return doubleval($price);
        }, $originalGuidancePrices);
        foreach ($originalGuidancePrices as $key => $originalGuidancePrice) {
            if (empty($originalGuidancePrice)) {
                unset($originalGuidancePrices[$key]);
            }
        }
        return [
            'min' => $originalGuidancePrices ? min($originalGuidancePrices) : 0,
            'max' => $originalGuidancePrices ? max($originalGuidancePrices) : 0,
        ];
    }
}
