<?php
namespace common\models\parts\gpubs;

use Yii;
use yii\base\InvalidConfigException;
use common\models\ObjectAbstract;
use common\ActiveRecord\ActivityGpubsProductAR;
use common\ActiveRecord\ActivityGpubsProductSkuAR;
use common\models\parts\Product;

class GpubsProduct extends ObjectAbstract{

    const STATUS_ACTIVE = 1;
    const STATUS_CLOSED = 0;
    const GPUBS_TYPE_INVITE     = 1;#商品类型:自提
    const GPUBS_TYPE_DELIVER    = 2;#商品类型:送货
    const HOT_RECOMMENT_NOT     = 1; #热门推荐 否
    const HOT_RECOMMENT_IS      = 2; #热门推荐 是
    const STATUS_GPUBS_RULE_MEMBER  = 1; #成团类型 按人数
    const STATUS_GPUBS_RULE_NUMBER  = 2; #成团类型 按数量
    const STATUS_GPUBS_PRE_NUMBER   = 3; #成团类型 按人数+数量

    public $id;

    private $_product;
    private $_activityProductSku;

    public function init(){
        if(!$this->AR = ActivityGpubsProductAR::findOne($this->id))throw new InvalidConfigException;
    }

    protected function _gettingList() : array{
        return [
            'product_id',
            'max_launch_per_user',
            'activity_start_datetime',
            'activity_start_unixtime',
            'activity_end_datetime',
            'activity_end_unixtime',
            'lifecycle_per_group',
            'min_quantity_per_group',
            'status',
            'min_member_per_group',
            'min_quantity_per_member_of_group',
            'gpubs_type',
            'gpubs_rule_type',
            'description',
        ];
    }

    protected function _settingList() : array{
        return [
            'status',
        ];
    }

    public function getProduct(){
        if(is_null($this->_product)){
            $this->_product = new Product([
                'id' => $this->AR->product_id,
            ]);
        }
        return $this->_product;
    }

    public function getActivityProductSku(){
        if(is_null($this->_activityProductSku)){
            if($sku = Yii::$app->RQ->AR(new ActivityGpubsProductSkuAR)->all([
                'select' => ['product_sku_id', 'price', 'stock'],
                'where' => [
                    'product_id' => $this->AR->product_id,
                ],
            ])){
                $this->_activityProductSku = array_combine(array_column($sku, 'product_sku_id'), $sku);
            }else{
                $this->_activityProductSku = false;
            }
        }
        return $this->_activityProductSku;
    }

    public function setGpubsProductSKU($productId, $sku)
    {
        $items = [];
        foreach ($sku as $key => $value) {
            $value['product_sku_id'] = $key;
            $items[] = $value;
        }
        $sku = new GpubsProductSkuGenerator([
            'productId' => $productId,
            'gpubsProductId' => $this->id,
            'items' => $items,
        ]);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$sku->generate()) throw new \Exception;
            $transaction->commit();
            return true;
        } catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    public function updateGpubsProductSKU(array $sku, $share_title, $share_subtitle, $filename)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->AR->share_title = $share_title;
            $this->AR->share_subtitle = $share_subtitle;
            $this->AR->filename = $filename;
            if (!$this->AR->save(false)) {
                throw new \Exception;
            }
            foreach ($sku as $skuId => $fields) {
                if ($skuAR = ActivityGpubsProductSkuAR::findOne(['product_sku_id' => $skuId, 'activity_gpubs_product_id' => $this->id])) {
                    foreach ($fields as $fieldName => $fieldValue) {
                        if ($fieldName == 'stock') {
                            $skuAR->$fieldName = $fieldValue;
                        } else {
                            continue;
                        }
                        if (($queryResult = $skuAR->update()) === false) throw new \Exception;
                    }
                } else {
                    throw new \Exception;
                }
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }
}
