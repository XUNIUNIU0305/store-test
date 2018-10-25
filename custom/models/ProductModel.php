<?php
namespace custom\models;

use Yii;
use common\models\Model;
use common\models\parts\Product;
use common\components\handler\ProductHandler;
use common\models\parts\Item;
use custom\models\parts\UrlParamCrypt;
use custom\models\parts\ItemInCart;
use yii\helpers\Url;
use common\ActiveRecord\ProductSPUOptionAR;

class ProductModel extends Model{

    const SCE_GET_INFO = 'get_product_info';
    const SCE_ADD_CART = 'add_cart';
    const SCE_PLACE_ORDER = 'place_order';

    public $id;
    public $product_id;
    public $sku_id;
    public $count;

    public function scenarios(){
        return [
            self::SCE_GET_INFO => [
                'id',
            ],
            self::SCE_ADD_CART => [
                'product_id',
                'sku_id',
                'count',
            ],
            self::SCE_PLACE_ORDER => [
                'product_id',
                'sku_id',
                'count',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['id', 'product_id', 'sku', 'count'],
                'required',
                'message' => 9001,
            ],
            [
                ['id', 'product_id'],
                'common\validators\product\IdValidator',
                'saleStatus' => [
                    Product::SALE_STATUS_ONSALE,
                    Product::SALE_STATUS_UNSOLD,
                ],
                'message' => 3001,
            ],
            [
                ['count'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['sku_id'],
                'common\validators\ShoppingCart\SkuIdValidator',
                'productId' => $this->product_id,
                'userId' => $this->scenario == self::SCE_ADD_CART ? Yii::$app->user->id : null,
                'count' => $this->count,
                'message' => 3031,
            ],
        ];
    }

    public function placeOrder(){
        //邀请门店强制提交信息
        //if(!Yii::$app->CustomUser->CurrentUser->isAuthorized){
            //$this->addError('addCart', 3033);
            //return false;
        //}
        $item = new Item(['id' => $this->sku_id]);
        if(Yii::$app->CustomUser->CurrentUser->level < $item->productObj->customerLimit){
            $this->addError('addCart', 3034);
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(Yii::$app->CustomUser->cart->existItem((int)$this->sku_id)){
                Yii::$app->CustomUser->cart->removeItem(new ItemInCart(['id' => $this->sku_id]));
            }
            Yii::$app->CustomUser->cart->addItem($item, $this->count);
            $transaction->commit();
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('placeOrder', 3141);
            return false;
        }
        $urlCrypt = new UrlParamCrypt;

        $q = $urlCrypt->encrypt((array)$this->sku_id);
        return [
            'url' => Url::to(['/confirm-order', 'q' => $q]),
        ];
    }

    public function getProductInfo(){
        $product = new Product([
            'id' => $this->id,
        ]);
        return ProductHandler::getMultiAttributes($product, [
            'category',
            'supplier',
            'title',
            'description',
            'purchase_location' => 'purchaseLocation',
            'invoice',
            'warranty',
            'customization',
            'customer_limit' => 'customerLimit',
            'big_images' => 'bigImages',
            'detail',
            'SPU',
            'SKU',
            'price' => Yii::$app->user->isGuest ? 'guidancePrice' : 'price',
            'original_price' => Yii::$app->user->isGuest ? 'originalGuidancePrice' : 'originalPrice',
            'sales',
            'paid',
            '_func' => [
                'bigImages' => function($image){
                    return $image->path;
                },
                'SPU' => function($SPU){
                    return array_map(function($attributes){
                        return ProductHandler::getMultiAttributes($attributes, [
                            'id',
                            'name',
                            'options',
                            'selected_option' => 'selectedOption',
                            '_func' => [
                                'selectedOption' => function($selectedOptionId){
                                    if($AR = ProductSPUOptionAR::findOne($selectedOptionId)){
                                        return [
                                            'id' => $selectedOptionId,
                                            'name' => $AR->name,
                                        ];
                                    }else{
                                        return [
                                            'id' => 0,
                                            'name' => '',
                                        ];
                                    }
                                },
                            ],
                        ]);
                    }, $SPU->attributesWithOptions);
                },
                'SKU' => function($SKU){
                    return [
                        'attributes' => $SKU->attributeWithOption,
                        'sku' => array_map(function($sku){
                            return ProductHandler::getMultiAttributes($sku, [
                                'id',
                                'guidance_price',
                                'price',
                                'stock',
                                'custom_id',
                                'bar_code',
                                'original_price',
                                'original_guidance_price',
                                '_func'=>[
                                    'price'=>function($price){
                                        return (Yii::$app->user->isGuest)?0:$price;
                                    },
                                    'original_price' => function($original_price) {
                                        return Yii::$app->user->isGuest ? 0 : $original_price;
                                    },
                                ],
                            ]);
                        }, $SKU->SKU),
                    ];
                },
            ],
        ]);
    }

    public function addCart(){
        //邀请门店强制提交信息
        //if(!Yii::$app->CustomUser->CurrentUser->isAuthorized){
            //$this->addError('addCart', 3033);
            //return false;
        //}
        $item = new Item(['id' => $this->sku_id]);
        if(Yii::$app->CustomUser->CurrentUser->level < $item->productObj->customerLimit){
            $this->addError('addCart', 3034);
            return false;
        }
        if(Yii::$app->CustomUser->cart->addItem($item, intval($this->count), false)){
            return true;
        }else{
            $this->addError('addCart', 3032);
            return false;
        }
    }
}
