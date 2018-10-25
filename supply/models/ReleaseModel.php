<?php
namespace supply\models;

use Yii;
use common\models\Model;
use common\models\parts\ProductCategory;
use common\models\parts\CategoryAttribute;
use supply\models\parts\OSSUploadConfigForSupply;
use common\models\parts\Product;
use common\models\parts\Attribute;
use common\models\parts\OSSImage;
use common\ActiveRecord\OSSUploadFileAR;
use common\ActiveRecord\ProductCategoryAR;
use common\components\handler\Handler;
use common\models\parts\custom\CustomUser;

class ReleaseModel extends Model{

    const SCE_GET_CATEGORY = 'get_category';
    const SCE_GET_ATTRIBUTE = 'get_attribute';
    const SCE_GET_PERMISSION = 'get_permission';
    const SCE_ADD_PRODUCT = 'add_product';
    const SCE_GET_PRODUCT_INFO = 'get_product_info';
    const SCE_GET_FULL_CATEGORY = 'get_full_category';
    const SCE_MODIFY_PRODUCT = 'modify_product';
    const SCE_ADD_KEYWORD = 'add_keyword';

    public $parent_id;
    public $category_id;
    public $file_suffix;
    public $attribute;
    public $title;
    public $description;
    public $purchase_location;
    public $invoice;
    public $warranty;
    public $customization;
    public $image;
    public $detail;
    public $mobile_detail;
    public $product_id;
    public $customer_limit;

    //商品搜索关键字
    public $keyword;

    public function scenarios(){
        return [
            self::SCE_GET_CATEGORY => [
                'parent_id',
            ],
            self::SCE_GET_ATTRIBUTE => [
                'category_id',
            ],
            self::SCE_GET_PERMISSION => [
                'file_suffix',
            ],
            self::SCE_ADD_PRODUCT => [
                'category_id',
                'attribute',
                'title',
                'description',
                'purchase_location',
                'invoice',
                'warranty',
                'customization',
                'customer_limit',
                'image',
                'detail',
                'mobile_detail',
            ],
            self::SCE_GET_PRODUCT_INFO => [
                'product_id',
            ],
            self::SCE_GET_FULL_CATEGORY => [
                'category_id',
            ],
            self::SCE_MODIFY_PRODUCT => [
                'product_id',
                'attribute',
                'title',
                'description',
                'image',
                'detail',
                'mobile_detail',
            ],
            self::SCE_ADD_KEYWORD=>[
                'keyword',
                'product_id',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['parent_id'],
                'default',
                'value' => '0',
            ],
            [
                ['mobile_detail'],
                'default',
                'value' => '',
            ],
            [
                ['parent_id', 'category_id', 'file_suffix', 'attribute', 'title', 'description', 'purchase_location', 'invoice', 'warranty', 'customization', 'image', 'detail', 'product_id', 'customer_limit'],
                'required',
                'message' => 9001,
                'except' => self::SCE_MODIFY_PRODUCT,
            ],
            [
                ['product_id'],
                'required',
                'message' => 9001,
                'on' => self::SCE_MODIFY_PRODUCT,
            ],
            [
                ['parent_id'],
                'integer',
                'min' => 0,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['title'],
                'string',
                'length' => [1, Product::TITLE_MAX_LENGTH],
                'message' => 9002,
                'tooShort' => 9002,
                'tooLong' => 9002,
            ],
            [
                ['description'],
                'string',
                'length' => [1, Product::DESCRIPTION_MAX_LENGTH],
                'message' => 9002,
                'tooShort' => 9002,
                'tooLong' => 9002,
            ],
            [
                ['detail', 'mobile_detail'],
                'string',
                'length' => [1, 20000],
                'message' => 9002,
                'tooShort' => 9002,
                'tooLong' => 9002,
            ],
            [
                ['purchase_location', 'invoice', 'warranty', 'customization'],
                'in',
                'range' => [0, 1],
                'message' => 9002,
            ],
            [
                ['product_id'],
                'common\validators\product\IdValidator',
                'userId' => Yii::$app->user->id,
                'message' => 9002,
            ],
            [
                ['category_id'],
                'exist',
                'targetClass' => ProductCategoryAR::className(),
                'targetAttribute' => 'id',
                'filter' => [
                    'display' => ProductCategory::STATUS_DISPLAY,
                ],
                'message' => 9002,
            ],
            [
                ['customer_level'],
                'in',
                'range' => CustomUser::getLevels(),
                'message' => 1171,
            ],
            [
                ['keyword'],
                'default',
                'value' => [],
            ],
        ];
    }

    public function modifyProduct(){
        $product = new Product(['id' => $this->product_id]);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if($this->attribute){
                $spu = $product->spu;
                foreach($this->attribute as $attribute => $selectedOption){
                    $spu->modifyOption($attribute, $selectedOption);
                }
            }
            if($this->title){
                $product->title = $this->title;
            }
            if($this->description){
                $product->description = $this->description;
            }
            if($this->image){
                if(count($this->image) < Product::BIG_IMG_MIN_COUNT || count($this->image) > Product::BIG_IMG_MAX_COUNT)throw new \Exception('not enough images');
                $OSSImage = new OSSImage(['images' => ['filename' => $this->image]]);
                $uploaderId = array_unique($OSSImage->uploaderId);
                if(count($uploaderId) > 1 || $uploaderId[0] != Yii::$app->user->id)throw new \Exception('unavailable image');
                $product->setBigImages($OSSImage, true);
                if(!$product->setMainImage($OSSImage))throw new \Exception('unable to set main image');
            }
            if(!is_null($this->detail)){
                $product->detail = $this->detail;
            }
            if(!is_null($this->mobile_detail)){
                $product->mobileDetail = $this->mobile_detail;
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('modifyProduct', 1141);
            return false;
        }
    }

    public function getFullCategory(){
        $category = new ProductCategory(['id' => $this->category_id]);
        do{
            $categoryList[] = [
                'id' => $category->id,
                'name' => $category->title,
            ];
        }while($category = $category->parentCategory);
        krsort($categoryList);
        return array_values($categoryList);
    }

    public function getProductInfo(){
        $product = new Product(['id' => $this->product_id]);
        return Handler::getMultiAttributes($product, [
            'title',
            'description',
            'purchase_location' => 'purchaseLocation',
            'invoice',
            'warranty',
            'customization',
            'customer_limit' => 'customerLimit',
            'detail',
            'mobile_detail' => 'mobileDetail',
            'big_images' => 'bigImages',
            'spu',
            'keyword'=>'keyword',
            '_func' => [
                'bigImages' => function($images){
                    return array_map(function($imageName){
                        return [
                            'name' => $imageName,
                            'path' => Yii::$app->params['OSS_PostHost'] . '/' . $imageName,
                        ];
                    }, $images->getName());
                },
                'spu' => function($spu){
                    return $spu->getSelectedOptions();
                },
                'keyword'=>function($keyword){
                    return $keyword ? explode(',',$keyword) : '';
                }
            ],
        ]);
    }

    /**
     * 获取次级分类
     *
     * @return array
     */
    public function getCategory(){
        if(!$this->validate())return false;
        if(ProductCategory::isParentCategory($this->parent_id)){
            return ProductCategory::getChildCategory($this->parent_id);
        }else{
            $this->addError('getCategory', 1011);
            return false;
        }
    }

    /**
     * 获取分类属性
     *
     * @return array
     */
    public function getAttribute(){
        if(!$this->validate())return false;
        if(!self::existEndCategory($this->category_id)){
            $this->addError('getAttribute', 1021);
            return false;
        }
        $category = new CategoryAttribute(['categoryId' => $this->category_id]);
        return $category->getAttributesWithOptions();
    }

    /**
     * 获取OSS上传授权
     *
     * @return array
     */
    public function getOSSUploadPermission(){
        if(!$this->validate())return false;
        $uploadConfig = new OSSUploadConfigForSupply([
            'userId' => Yii::$app->user->id,
            'fileSuffix' => $this->file_suffix,
        ]);
        if($permission = $uploadConfig->getPermission()){
            return $permission;
        }else{
            $this->addError('getOSSUploadPermission', 1031);
            return false;
        }
    }

    /**
     * 添加商品
     *
     * @return integer|false
     */
    public function addProduct(){
        $transaction = Yii::$app->db->beginTransaction();
        if($newProductId = $this->createProduct()){
            $transaction->commit();
            return $newProductId;
        }else{
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 创建商品
     *
     * @return integer|false
     */
    protected function createProduct(){
        if(!$this->validate())return false;
        if(!self::existEndCategory($this->category_id)){
            $this->addError('addProduct', 1042);
            return false;
        }
        if(!is_array($this->attribute)){
            $this->addError('addProduct', 1043);
            return false;
        }
        $attributeId = array_keys($this->attribute);
        $categoryId = (new CategoryAttribute(['categoryId' => $this->category_id]))->getAttributes();
        if(!empty(array_diff($categoryId, $attributeId))){
            $this->addError('addProduct', 1044);
            return false;
        }
        if(count($this->image) < Product::BIG_IMG_MIN_COUNT || count($this->image) > Product::BIG_IMG_MAX_COUNT){
            $this->addError('addProduct', 1048);
            return false;
        }
        if(count($this->image) != count(array_unique($this->image))){
            $this->addError('addProduct', 1049);
            return false;
        }
        try{
            foreach($this->attribute as $attribute => $option){
                $attributes[] = new Attribute([
                    'id' => $attribute,
                    'selectedOption' => $option,
                ]);
            }
        }catch(\Exception $e){
            $this->addError('addProduct', 1045);
            return false;
        }
        try{
            $images = new OSSImage([
                'images' => ['filename' => $this->image],
            ]);
            $imagesOwnerType = array_unique($images->getUploaderType());
            $imagesOwnerId = array_unique($images->getUploaderId());
            if(count($imagesOwnerType) > 1 || 
                count($imagesOwnerId) > 1 ||
                current($imagesOwnerType) != OSSUploadFileAR::SUPPLY_USER ||
                current($imagesOwnerId) != Yii::$app->user->id){
                throw new \Exception;
            }
        }catch(\Exception $e){
            $this->addError('addProduct', 1046);
            return false;
        }
        $productData = [
            'product_category_id' => $this->category_id,
            'supply_user_id' => Yii::$app->user->id,
            'title' => $this->title,
            'description' => $this->description,
            'purchase_location' => $this->purchase_location,
            'invoice' => $this->invoice,
            'warranty' => $this->warranty,
            'customization' => $this->customization,
            'customer_limit' => $this->customer_limit,
            'product_big_images_id' => 0,
            'detail' => $this->detail,
            'mobile_detail' => $this->mobile_detail,
        ];
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $product = Yii::$app->SupplyUser->product::generate($productData);
            if($product->setBigImages($images) &&
                $product->setMainImage($images) &&
                $product->bindSPUs($attributes)
            ){
                $transaction->commit();
                return $product->id;
            }else{
                throw new \Exception;
            }
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('addProduct', 1047);
            return false;
        }
    }

    /**
     * 获取OSS上传限制
     *
     * @return array
     */
    public static function getReleaseLimit(){
        $uploadConfig = new OSSUploadConfigForSupply();
        return array_merge($uploadConfig->getUploadLimit(), [
            'img_min_count' => Product::BIG_IMG_MIN_COUNT,
            'img_max_count' => Product::BIG_IMG_MAX_COUNT,
            'title_max_length' => Product::TITLE_MAX_LENGTH,
            'description_max_length' => Product::DESCRIPTION_MAX_LENGTH,
        ]);
    }

    /**
     * 验证是否存在终端分类
     *
     * @return boolean
     */
    public static function existEndCategory($categoryId){
        return ProductCategory::existEndCategory($categoryId);
    }


    /**
     *====================================================
     * 设置商品搜索关键字
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function addKeyword(){
        try {
            $keyword = implode(',',$this->keyword);
            $model = new Product(['id'=>$this->product_id]);
            if ($model->setKeyword($keyword) !== false){
                return true;
            }
        }catch (\Exception $exception){
            $this->addError('addKeyword',1200);
            return false;
        }
    }

}
