<?php
namespace mobile\modules\gpubs\models;

use Yii;
use common\models\Model;
use common\models\parts\Item;
use common\ActiveRecord\ActivityGpubsProductSkuAR;
use common\models\parts\gpubs\GpubsProductSku;

class DetailModel extends Model{

    const SCE_GET_PRODUCT_SKU = 'get_product_sku';

    public $product_sku;

    public function scenarios(){
        return [
            self::SCE_GET_PRODUCT_SKU => [
                'product_sku',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['product_sku'],
                'required',
                'message' => 9001,
            ],
            [
                ['product_sku'],
                'exist',
                'targetClass' => '\common\ActiveRecord\ActivityGpubsProductSkuAR',
                'targetAttribute' => ['product_sku' => 'product_sku_id'],
                'message' => 9002,
            ],
        ];
    }

    public function getProductSku(){
        $item = new Item([
            'id' => $this->product_sku,
        ]);
        if(!$sku = ActivityGpubsProductSkuAR::findOne([
            'product_sku_id' => $this->product_sku,
        ])){
            $this->addError('getProductSku', 10111);
            return false;
        }
        $gpubsProductSku = new GpubsProductSku([
            'activityGpubsProductId' => $sku->activity_gpubs_product_id,
            'productSkuId' => $sku->product_sku_id,
        ]);
        return [
            'title' => $item->title,
            'image' => $item->mainImage->path,
            'sku' => $item->attributes,
            'min_quantity_per_group' => $gpubsProductSku->product->min_quantity_per_group,
            'gpubs_type'=> $gpubsProductSku->product->gpubs_type,
            'min_member_per_group' => $gpubsProductSku->product->min_member_per_group,
            'gpubs_rule_type' => $gpubsProductSku->product->gpubs_rule_type,
            'min_quanlity_per_member_of_group'=> $gpubsProductSku -> product->min_quantity_per_member_of_group,
            'price' => $gpubsProductSku->price,
            'brand_name' => $item->getSupplier(true)->brandName,
            'description' => $gpubsProductSku->product->description,
        ];
    }
}
