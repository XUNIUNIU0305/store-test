<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/27
 * Time: 10:38
 */

namespace common\components\handler\coupon;


use common\ActiveRecord\CouponAR;
use common\ActiveRecord\CouponLogAR;
use common\components\handler\Handler;
use common\models\parts\coupon\Coupon;
use common\models\parts\supply\SupplyUser;
use Yii;
use yii\data\ActiveDataProvider;

class CouponLogHandler extends  Handler
{

    //创建优惠券券
    public static function create(Coupon $coupon,string $intro,$return="throw"){
        return Yii::$app->RQ->AR(new CouponLogAR())->insert([
            'log_intro'=>$intro,
            'coupon_id'=>$coupon->id,
            'log_time'=>time(),
        ],$return);
    }

    //查询优惠券列表
    public static function search(int $pageSize,int $currentPage,Coupon $coupon=null,$orderBy=['id'=>SORT_DESC]){
        $where="1";
        if($coupon!=null){
            $where.=" and coupon_id='$coupon->id'";
        }
        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 1;
        return new ActiveDataProvider([
            'query' => CouponLogAR::find()->select('id')->where($where)->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => $orderBy,
            ],
        ]);
    }




}