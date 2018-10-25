<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/9/22
 * Time: 下午3:16
 */

namespace custom\models;

use common\ActiveRecord\ProductAR;
use common\ActiveRecord\SupplyUserAR;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\Product;
use Yii;
class ProductRecommendModel extends Model
{
    const SCE_GOODS = 'get_goods';
    const SCE_GROUP_PURCHASE_GOODS = 'group_purchase_goods';
    public $id;

    public function rules()
    {
        return [
            [
                ['id'],
                'required',
                'message'=>9001,
            ],
            [
                ['id'],
                'each',
                'rule'=>[
                    'exist',
                    'targetClass'=>ProductAR::className(),
                    'targetAttribute'=>['id'=>'id'],
                    'message'=>3368,
                ],
                'on'=>[self::SCE_GOODS]
            ],
            //[
                //['id'],
                //'exist',
                //'targetClass'=>SupplyUserAR::className(),
                //'targetAttribute'=>['id'=>'id'],
                //'message'=>3368,
                //'on'=>[self::SCE_GROUP_PURCHASE_GOODS]
            //],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCE_GOODS => ['id'],
            self::SCE_GROUP_PURCHASE_GOODS => ['id'],
        ];
    }


    public function getGoods(){
        if (!is_array($this->id) || count($this->id) > 300) {
            $this->addError('good',3368);
            return false;
        }
        //商品id，实际价格区间，指导价格区间（非登入情况下），名称，卖点，是否定制
        return array_map(function($id){
            return Handler::getMultiAttributes(new Product(['id'=>$id]),[
                'id',
                'title',
                'description',
                'customization',
                'price' => Yii::$app->user->isGuest ? 'guidancePrice' : 'price',
                'main_image'=>'mainImage',
                '_func'=>[
                    'mainImage'=>function($img){
                        return $img->path;
                    },
                ]
            ]);
        },$this->id);
    }


    public function groupPurchaseGoods(){
        try {
            //供应商组合购买商品数组
            $group = [
                4=>[688,691,693,695,697],
                8=>[655,661,670,675,680]
            ];
            //商品id，实际价格区间，指导价格区间（非登入情况下），名称，卖点，是否定制
            return array_key_exists($this->id,$group) ? array_map(function($id){
                return Handler::getMultiAttributes(new Product(['id'=>$id]),[
                    'id',
                    'title',
                    'description',
                    'customization',
                    'price' => Yii::$app->user->isGuest ? 'guidancePrice' : 'price',
                    'main_image'=>'mainImage',
                    '_func'=>[
                        'mainImage'=>function($img){
                            return $img->path;
                        },
                    ]
                ]);
            },$group[$this->id]) : [];

        }catch (\Exception $exception){
            return [];
        }
    }
}
