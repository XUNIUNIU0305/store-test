<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/6/13
 * Time: 上午11:26
 */

namespace mobile\models;

use common\ActiveRecord\ProductAR;
use common\components\handler\Handler;
use common\models\parts\Product;
use custom\models\parts\temp\OrderLimit\ProductLimit;
use Yii;

class ProductRecommendModel extends \custom\models\ProductRecommendModel
{
    const SCE_RAND = 'rand_goods';

    public $page_size;
    public $current_page;

    public function scenarios()
    {
        $scenario = [
            self::SCE_RAND => ['page_size','current_page'],
        ];
        return array_merge(parent::scenarios(), $scenario);
    }

    public function rules()
    {
        $rule = [
            [
                ['page_size'],
                'default',
                'value'=>6
            ],
            [
                ['current_page'],
                'default',
                'value'=>1
            ],
            [
                ['page_size','current_page'],
                'required',
                'message'=>9001,
            ]

        ];

        return array_merge(parent::rules(), $rule);
    }


    public function randGoods(){
        $level = Yii::$app->user->isGuest ? 2 : Yii::$app->CustomUser->CurrentUser->level;
        $key = Yii::$app->user->id.'_'.$level.'_rand_product_id';
        if ($sessionProductId = Yii::$app->session->get($key)){
            if ($this->current_page == 1){
                shuffle($sessionProductId);
                Yii::$app->session->set($key,$sessionProductId);
            }
        }else {
            $limitProductId = ProductLimit::getLimitProductId();
            $sessionProductId = ProductAR::find()->select('id')
                ->where(['sale_status'=>Product::SALE_STATUS_ONSALE])
                ->andWhere(['<=','customer_limit',$level])
                ->andWhere(['not in','id',$limitProductId])
                ->column();
            shuffle($sessionProductId);
            Yii::$app->session->set($key,$sessionProductId);
        }
        $randIds = array_slice($sessionProductId,$this->page_size*($this->current_page -1),$this->page_size,true);

        //商品id，实际价格区间，指导价格区间（非登入情况下），名称，卖点，是否定制
        return $randIds ? array_map(function($id){
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
        },array_values($randIds)) : [];

    }
}