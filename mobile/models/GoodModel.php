<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/6/13
 * Time: 上午11:26
 */

namespace mobile\models;


use common\ActiveRecord\ProductSPUOptionAR;
use common\components\handler\OSSImageHandler;
use common\components\handler\ProductHandler;
use common\models\parts\OSSImage;
use common\models\parts\Product;
use custom\models\ProductModel;
use Yii;

class GoodModel extends ProductModel
{
    const SCE_GET_GOOD_INFO = 'get_good_info';

    public function scenarios()
    {
        $scenario=[
            self::SCE_GET_GOOD_INFO=>['id'],
        ];
        return array_merge(parent::scenarios(),$scenario);
    }

    public function rules()
    {
        return parent::rules();
    }

    public function getGoodInfo(){
        $product = new Product([
            'id' => $this->id,
        ]);
        $mobileDetailFunc = function ($detail) use($product) {
            return empty($detail) ? $product->detail: $detail;
        };
        return ProductHandler::getMultiAttributes($product, [
            'category',
            'supplier',
            'title',
            'description',
            'purchase_location' => 'purchaseLocation',
            'invoice',
            'warranty',
            'customization',
            'big_images' => 'bigImages',
            'detail'=>'mobileDetail',
            'SPU',
            'SKU',
            'price' => Yii::$app->user->isGuest ? 'guidancePrice' : 'price',
            'original_price' => Yii::$app->user->isGuest ? 'originalGuidancePrice' : 'originalPrice',
            'sales',
            'paid',
            '_func' => [
                'mobileDetail'=> $mobileDetailFunc,
                'bigImages' => function($image){
                    if (is_array($image->images)) {
                        return array_map(function($id){
                            $ossImageHandlerObj = OSSImageHandler::load(new OSSImage([
                                'images' => $id,
                            ]));
                            $ossSize = $ossImageHandlerObj->resize(375,375,0);
                            return $ossSize->apply() ? $ossSize->image->path : '';
                        },current($image->images));

                    }elseif(is_numeric($image->images)){
                        $ossImageHandlerObj = OSSImageHandler::load(new OSSImage([
                            'images' => $image->images,
                        ]));
                        $ossSize = $ossImageHandlerObj->resize(375,375,0);
                        return $ossSize->apply() ? $ossSize->image->path : '';
                        
                    }else{
                        return '';
                    }
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



}