<?php
/**
 * User: JiangYi
 * Date: 2017/5/27
 * Time: 16:23
 * Desc:
 */

namespace wechat\models;


use common\ActiveRecord\CustomUserTradeAR;

use custom\models\parts\trade\Trade;
use Yii;

class TradeModel extends \custom\models\TradeModel {


    const SCE_GET_ORDER_LIST='get_order_list';


    public $trade_id;


    public function scenarios()
    {
        $scenario= [
            self::SCE_GET_ORDER_LIST=>['trade_id'],
        ];
        return array_merge(parent::scenarios(),$scenario);
    }

    public function rules()
    {
        $rules= [
            [
                ['trade_id'],
                'exist',
                'targetClass'=>CustomUserTradeAR::className(),
                'targetAttribute'=>['trade_id'=>'id','CustomId'=>'custom_user_id'],
                'message'=>10006,
            ],
        ];
        return array_merge(parent::rules(),$rules);
    }


    /**
     * Author:JiangYi
     * Date:2017/5/27
     * Desc:返回当前customID
     * @return int|string
     */
    protected  function getCustomId(){
        return Yii::$app->user->id;
    }


    public function getOrderList(){

        $trade=new Trade(['id'=>$this->trade_id]);
        $orderList=$trade->getOrders();
        return array_map(function($item){
            $supply=$item->getSupplier();
            return [
                'id'=>$item->id,
                'order_code'=>$item->getOrderNo(),
                'order_total'=>$item->getTotalFee(),
                'supplier'=>[
                    'id'=>$supply->id,
                    'store_name'=>$supply->getStoreName(),
                    'company_name'=>$supply->getCompanyName(),
                    'brand_name'=>$supply->getBrandName(),
                ],

            ];
        },$orderList);




    }



}