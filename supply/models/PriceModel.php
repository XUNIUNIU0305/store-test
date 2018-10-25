<?php
namespace supply\models;

use Yii;
use common\models\Model;
use common\models\parts\Product;
use common\models\parts\ProductSKUGenerator;
use common\ActiveRecord\ProductSKUAR;
use common\models\parts\ProductSKU;
use common\models\parts\ItemPrice;

class PriceModel extends Model{

    const SCE_SHOW_PAGE = 'show_page';
    const SCE_ADD_SKU = 'add_sku';
    const SCE_CURRENT_SKU = 'current_sku';
    const SCE_MODIFY_SKU = 'modify_sku';
    const SCE_CALCULATE_PRICE = 'calculate_price';

    public $product_id;
    public $attrs;
    public $cartesian;
    public $sku;
    public $cost_price;

    public function scenarios(){
        return [
            self::SCE_SHOW_PAGE => [
                'product_id',
            ],
            self::SCE_ADD_SKU => [
                'product_id',
                'attrs',
                'cartesian',
            ],
            self::SCE_CURRENT_SKU => [
                'product_id',
            ],
            self::SCE_MODIFY_SKU => [
                'product_id',
                'sku',
            ],
            self::SCE_CALCULATE_PRICE => [
                'cost_price',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['product_id', 'attrs', 'cartesian', 'sku', 'cost_price'],
                'required',
                'message' => 9001,
            ],
            [
                ['product_id'],
                'common\validators\product\IdValidator',
                'message' => 1051,
                'userId' => Yii::$app->user->id,
            ],
            [
                ['cartesian'],
                'common\validators\product\CartesianValidator',
                'attrs' => $this->attrs,
                'contain' => [
                    'cost_price' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0,
                    ],
                    'guidance_price' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0,
                    ],
                    'stock' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0,
                    ],
                    'custom_id' => [
                        'class' => 'yii\validators\StringValidator',
                        'max' => ProductSKUAR::CUSTOM_ID_MAX_LENGTH,
                    ],
                    'bar_code' => [
                        'class' => 'yii\validators\StringValidator',
                        'max' => ProductSKUAR::BAR_CODE_MAX_LENGTH,
                    ],
                    'original_price' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0,
                    ],
                    'original_guidance_price' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0,
                    ],
                ],
                'validateSkuRule' => false,
                'message' => 1052,
            ],
            [
                ['sku'],
                'common\validators\product\SkuValidator',
                'productId' => $this->product_id,
                'canContain' => [
                    'cost_price' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0,
                    ],
                    'guidance_price' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0,
                    ],
                    'stock' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0,
                    ],
                    'custom_id' => [
                        'class' => 'yii\validators\StringValidator',
                        'max' => ProductSKUAR::CUSTOM_ID_MAX_LENGTH,
                    ],
                    'bar_code' => [
                        'class' => 'yii\validators\StringValidator',
                        'max' => ProductSKUAR::BAR_CODE_MAX_LENGTH,
                    ],
                    'original_price' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0,
                    ],
                    'original_guidance_price' => [
                        'class' => 'yii\validators\NumberValidator',
                        'min' => 0,
                    ],
                ],
                'message' => 1061,
            ],
            [
                ['cost_price'],
                'number',
                'min' => 0,
                'tooSmall' => 1101,
                'message' => 1101,
            ],
        ];
    }

    public function calculatePrice(){
        return [
         'price' => (new ItemPrice)->generatePrice((float)$this->cost_price),
        ];
    }

    /**
     * 添加sku
     *
     * @return boolean
     */
    public function addSKU(){
        if(!$this->validate())return false;
        $product = new Product([
            'id' => $this->product_id,
        ]);
        $result = $product->setSKU($this->attrs, $this->cartesian);
        if($result){
            return true;
        }else{
            $this->addError('addProductSKU', 1053);
            return false;
        }
    }

    /**
     * 获取分隔符
     *
     * @return array
     */
    public static function getCartesianSplit(){
        return [
            'key_value' => ProductSKUGenerator::KEY_VALUE_SPLIT,
            'attrs' => ProductSKUGenerator::ATTRS_SPLIT,
        ];
    }

    /**
     * 获取商品当前sku
     *
     * @return false|array
     */
    public function getCurrentSKU(){
        if(!$this->validate())return false;
        $productSKU = new ProductSKU(['productId' => $this->product_id]);
        return [
            'attributes' => $productSKU->getAttributeWithOption(),
            'sku' => $productSKU->getSKU(),
        ];
    }

    /**
     * 修改商品sku
     *
     * @return boolean
     */
    public function modifySKU(){
        if(!$this->validate())return false;
        $product = new Product([
            'id' => $this->product_id,
        ]);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(!$product->sku->modify($this->sku))throw new \Exception;
            if($product->setPrice(array_values($product->sku->priceInterval)) === false)throw new \Exception;
            $transaction->commit();
            $result = true;
        }catch(\Exception $e){
            $transaction->rollBack();
            $result = false;
        }
        if($result){
            return true;
        }else{
            $this->addError('modifySku', 1062);
            return false;
        }
    }
}
