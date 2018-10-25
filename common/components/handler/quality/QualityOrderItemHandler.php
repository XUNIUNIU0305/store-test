<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 11:44
 */

namespace common\components\handler\quality;



use common\ActiveRecord\QualityOrderAR;

use common\ActiveRecord\QualityOrderItemAR;
use common\components\handler\Handler;
use common\models\parts\quality\QualityOrder;
use common\models\parts\quality\QualityPackage;
use Yii;
use yii\base\Exception;

class QualityOrderItemHandler extends  Handler
{


    //查询获取订单项
    public static function getList(QualityOrder $order){
        return Yii::$app->RQ->AR(new QualityOrderItemAR())->all([
            'where'=>['quality_order_id'=>$order->id],
            'select'=>['id'],
            'orderBy'=>['id'=>SORT_ASC],
        ]);
    }

    //创建订单项
    public static function create(int $order_id,array $orderItem=null,$return="throw"){


        $transaction=Yii::$app->db->beginTransaction();
        try {

            foreach ($orderItem as $key => $var) {
                $data=[
                    'code'=>$var['round_num'],
                    'quality_package_id'=>$var['package_id'],
                    'quality_place_id'=>$var["place_id"],
                    'quality_order_id'=>$order_id,
                    'sales'=>$var['sales'],
                    'custom_user_technician_id'=>$var['technician'],
                    'work_option'=>isset($var['work_option']) ? $var['work_option'] : '',
                ];
                if(!Yii::$app->RQ->AR(new QualityOrderItemAR())->insert($data,$return)){
                    $transaction->rollBack();
                    return false;
                }
            }
            $transaction->commit();
            return true;
        }catch (Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    //创建产品序列号
    private static function createCode($package,$code){
        return (new QualityPackage(['id'=>$package]))->getName().date("Ymd").$code;
    }

    // 获取膜的品牌
    public static function getMembraneBrand(QualityOrder $order)
    {
        $itemCode = Yii::$app->RQ->AR(new QualityOrderItemAR())->scalar([
            'select' => ['code'],
            'where' => ['quality_order_id' => $order->id],
        ]);
        if ($itemCode) {
            if (strpos($itemCode, 'DHTY') === 0) {
                return '天御';
            } elseif (strpos($itemCode, 'DH') === 0) {
                return '欧帕斯';
            } elseif (strpos($itemCode, 'UN') === 0) {
                return '悠耐(YONINE)';
            }
        }
        return '未知';
    }

}