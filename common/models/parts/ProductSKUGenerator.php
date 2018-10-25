<?php
namespace common\models\parts;

use Yii;
use yii\base\Object;
use common\models\RapidQuery;
use common\ActiveRecord\ProductSKUAR;
use common\ActiveRecord\ProductSKUAttributeAR;
use common\ActiveRecord\ProductSKUOptionAR;
use yii\base\InvalidConfigException;
use common\traits\CartesianTrait;
use common\models\parts\Product;

class ProductSKUGenerator extends Object{

    use CartesianTrait;

    //属性、选项分隔符
    const KEY_VALUE_SPLIT = ':';
    //属性之间分隔符
    const ATTRS_SPLIT = ';';

    //商品id
    public $productId;

    /**
     * $attrs sku属性和选项
     * [
     *     [attr_name1 => [opt_name1_1, opt_name1_2]],
     *     [attr_name2 => [opt_name2_1, opt_name2_2]],
     * ]
     * $cartesian 笛卡尔积组合对应数据
     * [
     *     [sku_id1 => ['cost_price' => 1, 'stock' => 1, ...]],
     *     [sku_id2 => ['cost_price' => 2, 'stock' => 2, ...]],
     * ]
     *
     * 详见 common\models\parts\Product
     */
    public $attrs;
    public $cartesian;

    //默认字段值
    public $defaultFieldValues = [
        'cost_price' => 0,
        'guidance_price' => 0,
        'price' => 0,
        'stock' => 0,
        'custom_id' => '',
        'bar_code' => '',
        'original_price' => 0,
        'original_guidance_price' => 0,
    ];

    //将属性存入数据库后重新生成的属性array
    protected $localAttrs;

    public function init(){
        if(empty($this->productId) ||
            empty($this->attrs) ||
            !is_array($this->attrs))throw new InvalidConfigException;
    }

    /**
     * 生成sku
     *
     * @return boolean
     */
    public function generate(){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(!$this->makeLocalAttrs())throw new \Exception;
            $outerCartesian = $this->generateCartesianProduct($this->generateCombo($this->attrs, self::KEY_VALUE_SPLIT), self::ATTRS_SPLIT);
            $localCartesian = $this->generateCartesianProduct($this->generateCombo($this->localAttrs, self::KEY_VALUE_SPLIT), self::ATTRS_SPLIT);
            if(!$cartesianData = $this->generateCartesianData())throw new \Exception;
            if(!$this->saveCartesian($outerCartesian, $localCartesian, $cartesianData))throw new \Exception;
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 生成笛卡尔积数据
     *
     * 实际是生成平台售价
     *
     * @return array
     */
    protected function generateCartesianData(){
        if(!is_array($this->cartesian))return false;
        $itemPrice = new ItemPrice;
        return array_map(function($v)use($itemPrice){
            if(isset($v['cost_price']) && $v['cost_price'] >= 0){
                $v['price'] = $itemPrice->generatePrice((float)$v['cost_price']);
            }
            return $v;
        }, $this->cartesian);
    }

    /**
     * 保存sku
     *
     * 如果某个笛卡尔积值或数据缺失则使用默认字段值填充
     *
     * @param array $outerCartesian 由$this->attrs计算出的笛卡尔积
     * @param array $localCartesian 由$this->localAttrs计算出的笛卡尔积
     * @param array $cartesianData 外部传入的笛卡尔积数据
     *
     * @return boolean
     */
    protected function saveCartesian($outerCartesian, $localCartesian, $cartesianData){
        $transaction = Yii::$app->db->beginTransaction();
        $saveFields = array_keys($this->defaultFieldValues);
        try{
            foreach($outerCartesian as $key => $skuId){
                $dataMergedDefault = isset($cartesianData[$skuId]) ? array_merge($this->defaultFieldValues, $cartesianData[$skuId]) : $this->defaultFieldValues;
                $customData = array_filter($dataMergedDefault, function($key)use($saveFields){
                    return in_array($key, $saveFields);
                }, ARRAY_FILTER_USE_KEY);
                $saveData = array_merge($customData, [
                    'product_id' => $this->productId,
                    'supply_user_id' => (new Product(['id' => $this->productId]))->supplier,
                    'sku_cartesian' => $localCartesian[$key],
                ]);
                if(!(new RapidQuery(new ProductSKUAR))->insert($saveData))throw new \Exception;
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 将外部属性存入数据库，生成本地属性
     *
     * @return boolean
     */
    protected function makeLocalAttrs(){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($this->attrs as $attr){
                foreach($attr as $attrName => $options){
                    if(!(new RapidQuery(new ProductSKUAttributeAR))->insert([
                        'product_id' => $this->productId,
                        'name' => $attrName,
                    ]))throw new \Exception;
                    $attrId = Yii::$app->db->getLastInsertID();
                    foreach($options as $option){
                        if(!(new RapidQuery(new ProductSKUOptionAR))->insert([
                            'product_id' => $this->productId,
                            'product_sku_attribute_id' => $attrId,
                            'name' => $option,
                        ]))throw new \Exception;
                        $this->localAttrs[$attrId][$attrName][Yii::$app->db->getLastInsertID()] = $option;
                    }
                }
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
}
