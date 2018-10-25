<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/27
 * Time: 10:38
 */

namespace common\components\handler\coupon;


use common\ActiveRecord\CouponAR;
use common\components\handler\Handler;
use common\models\parts\coupon\Coupon;
use common\models\parts\supply\SupplyUser;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;

class CouponHandler extends  Handler
{


    //更新所有过期优惠券状态
    public static function updateExpire(){
        return CouponAR::updateAll(['status'=>Coupon::STATUS_FINISHED],'end_time<'.time().' and status!='.Coupon::STATUS_DEL);
    }

    //创建优惠券券
    public static function create(string $name,float $cost,int $quantity,int $startTime,int $endTime,float $price_limit,int $personal_limit,SupplyUser $seller=null,$return="throw"){

        $transaction=Yii::$app->db->beginTransaction();
        try{
            if(!$id=Yii::$app->RQ->AR(new CouponAR())->insert([
                'name'=>$name,
                'price'=>$cost,
                'total_limit'=>$price_limit,
                'receive_limit'=>$personal_limit,
                'total_quantity'=>$quantity,
                'send_quantity'=>0,
                'start_time'=>$startTime,
                'end_time'=>$endTime,
                'supply_user_id'=>$seller==null?0:$seller->id,
                'status'=>Coupon::STATUS_NORMAL,
            ],$return)){
                //写入失败
                $transaction->rollBack();
                return false;
            }
            //计算长度，及最大可发行量
            $max=10-strlen($id);
            $maxCode="";
            for($i=0;$i<$max;$i++){
                $maxCode.="9";
            }

            if($quantity>$maxCode){
                //如果发行量大于最大可发行量，则取消退回
                $transaction->rollBack();
                return false;
            }

            $coupon=new Coupon(['id'=>$id]);
            if(!$coupon->setMaxQuantity($maxCode)||!$coupon->setMaxCode(1)){
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();
            return true;
        }catch(Exception $e){
            $transaction->rollBack();
            return false;
        }

    }

    //查询优惠券列表
    public static function search(int $pageSize,int $currentPage,int $status=null,$name=null,$orderBy=['id'=>SORT_DESC]){
        $where="1";
        if($status!==null&&in_array($status,[
            Coupon::STATUS_NORMAL,
            Coupon::STATUS_DEL,
            Coupon::STATUS_STOP,
            Coupon::STATUS_FINISHED
        ])){
            $where.=" and status='$status'";
        }
        if($name!==null){
            $where.=" and name like '%$name%'";
        }
        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 1;
        return new ActiveDataProvider([
            'query' => CouponAR::find()->select('id')->where($where)->asArray(),
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