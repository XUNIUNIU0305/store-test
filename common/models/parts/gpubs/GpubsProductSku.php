<?php
namespace common\models\parts\gpubs;

use Yii;
use yii\base\InvalidConfigException;
use common\models\ObjectAbstract;
use common\ActiveRecord\ActivityGpubsProductSkuAR;

class GpubsProductSku extends ObjectAbstract{

    public $id;
    public $activityGpubsProductId;
    public $productSkuId;

    private $_product;

    public function init(){
        if($this->id){
            $this->AR = ActivityGpubsProductSkuAR::findOne($this->id);
        }elseif($this->activityGpubsProductId && $this->productSkuId){
            $this->AR = ActivityGpubsProductSkuAR::findOne([
                'activity_gpubs_product_id' => $this->activityGpubsProductId,
                'product_sku_id' => $this->productSkuId,
            ]);
        }
        if($this->AR){
            $this->id = $this->AR->id;
            $this->activityGpubsProductId = $this->AR->activity_gpubs_product_id;
            $this->productSkuId = $this->AR->product_sku_id;
        }else{
            throw new InvalidConfigException;
        }
    }

    protected function _gettingList() : array{
        return [
            'product_id',
            'price',
            'stock',
        ];
    }

    protected function _settingList() : array{
        return [];
    }

    public function getProduct(){
        if(is_null($this->_product)){
            $this->_product = new GpubsProduct([
                'id' => $this->AR->activity_gpubs_product_id,
            ]);
        }
        return $this->_product;
    }
}
