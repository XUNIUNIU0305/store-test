<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/9/22
 * Time: 下午3:16
 */

namespace custom\models;


use common\ActiveRecord\ProductAR;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\Product;
use Yii;
class IndexProductModel extends Model
{
    const SCE_GOODS = 'get_goods';
    public $id;

    public function rules()
    {
        return [
            [
                ['id'],
                'require',
                'message'=>9001,
            ],
            [
                ['id'],
                'each',
                'rule'=>[
                    'exist',
                    'targetClass'=>ProductAR::className(),
                    'targetAttribute'=>['id'=>'id'],
                    'message'=>5228,
                ],
            ],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCE_GOODS => ['id'],
        ];
    }


    public function getGoods(){
        if (!is_array($this->id) || count($this->id) > 300) {
            $this->addErrors('good',3368);
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
            ]);
        },$this->id);
    }
}