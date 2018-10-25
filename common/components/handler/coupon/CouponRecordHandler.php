<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/27
 * Time: 10:38
 */

namespace common\components\handler\coupon;


use common\ActiveRecord\CouponRecordAR;
use common\components\handler\Handler;
use common\models\parts\coupon\Coupon;
use common\models\parts\coupon\CouponRecord;
use common\models\parts\custom\CustomUser;
use Yii;
use yii\base\Exception;
use yii\base\InvalidCallException;
use yii\data\ActiveDataProvider;


class CouponRecordHandler extends Handler
{
    const DEFAULT_PAGE_SIZE = 1000;


    //注册所有优惠券
    public static function disableTicket(Coupon $coupon){
        $where="coupon_id='".$coupon->id."' and (status='".CouponRecord::STATUS_EXCITED."' or status='".CouponRecord::STATUS_ACTIVE."')";
        return CouponRecordAR::updateAll(['status'=>CouponRecord::STATUS_CANCEL],$where)>=0?true:false;
    }

    //获取未优惠券数量
    public static function getCouponRecordQuantity($status){
        return Yii::$app->RQ->AR(new CouponRecordAR())->count(['where'=>['status'=>$status]]);
    }



    //搜索优惠券领 取记录
    public static function search(int $pageSize, int $currentPage, Coupon $coupon = null, CustomUser $user = null, $status = null, $orderBy = ['id' => SORT_DESC])
    {
        //检测所有未使用的过期券，并更新其状态
        //self::updateExpire();

        $where = "1";
        if ($coupon !== null) {
            $where .= " and coupon_id='$coupon->id'";
        }
        if ($user != null) {
            $where .= " and custom_user_id='$user->id'";
        }
        if ($status !== null) {
            $where .= " and status='$status'";
        }


        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 1;
        return new ActiveDataProvider([
            'query' => CouponRecordAR::find()->select('id')->where($where)->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => $orderBy,
            ],
        ]);

    }

    //更新过期记录
    public  static function updateExpire()
    {
        return CouponRecordAR::updateAll([
            'status' => CouponRecord::STATUS_EXPIRE,
        ], "expire<'" . time() . "' and (status='" . CouponRecord::STATUS_EXCITED . "' or status='" . CouponRecord::STATUS_ACTIVE . "')");
    }

    //创建优惠券券
    public static function create(Coupon $coupon, CustomUser $user = null, int $quantity, int $status, int $activeTime = 0, $custom_user_trade_id = 0, $return = "throw")
    {
        $userId = $user == null ? 0 : $user->id;
        //配置字段
        $column = ['coupon_id', 'code', 'password', 'custom_user_id', 'expire', 'status', 'active_time','create_time', 'custom_user_trade_id'];
        $pageSize = self::DEFAULT_PAGE_SIZE;


        try {
            //计算分页处理创建动作
            $pageCount = ceil($quantity / $pageSize);

            for ($i = 1; $i <= $pageCount; $i++) {
                //echo microtime() . '  ';
                $qty = $pageSize;
                $total = $i * $pageSize;
                //计算最后一页创建数量
                if ($total > $quantity) $qty = $quantity - (($i - 1) * $pageSize);
                //开启事务
                $transaction = Yii::$app->db->beginTransaction();
                try{
                    $maxCode = $coupon->max_code;

                    $rows = self::createRows($coupon, $maxCode, $userId, $qty, $status, $activeTime, $custom_user_trade_id);

                    if (!Yii::$app->db->createCommand()->batchInsert(CouponRecordAR::tableName(), $column, $rows)->execute()) {
                       throw new \Exception();
                    }
                    //更新相关属性
                    if (!$coupon->updateAttribute($qty, $maxCode)) {
                        throw new \Exception();
                    }
                    $transaction->commit();
                }catch (\Exception $e){
                    $transaction->rollBack();
                }
                // echo microtime() . PHP_EOL;
            }
            //更新已发生数量


            return true;
        } catch (\Exception $e) {
            return false;
        }

    }


    //创建取值
    private static function createRows(Coupon $coupon, &$maxCode, $userId, $quantity, $status, $activeTime, $custom_user_trade_id = 0)
    {
        $rows = [];

        for ($i = 0; $i < $quantity; $i++) {
            $rows[] = [
                'coupon_id' => $coupon->id,
                'code' => self::createCode($coupon->id, $maxCode, $coupon->max_quantity),
                'password' => self::createPassword(),
                'custom_user_id' => $userId,
                'expire' => $coupon->end_time,
                'status' => $status,
                'active_time' => $activeTime,
                'create_time'=>time(),
                'custom_user_trade_id' => $custom_user_trade_id,
            ];
            $maxCode++;
        }

        return $rows;
    }


    //创建优惠券代码
    /*
     *长度15位
     * 年（2) 月(2), coupon->id , 0 , 序号，（序号左侧用0 补充）
     */
    private static function createCode($id, $maxCode, $maxQuantity)
    {
        //使用0补充左侧空位
        $code = substr(strval($maxCode + $maxQuantity + 1), 1, strlen($maxQuantity));
         return date("ym")  . $code . "0". $id;
    }



    //创建优惠券密码
    /*
     * 8位
     * 英文字母大小写，加数字
     *ILOZ大小写去掉
     * 012去掉
     */
    private static function createPassword($length = 8)
    {
        $stringRange = "abcdefghjkmnpqrstuvwxyABCDEFGHJKMNPQRSTUVWXY3456789";
        // $password="";
        // for($i=0;$i<$length;$i++){
        $password = substr(str_shuffle($stringRange), 0, $length);
        // }
        return $password;
    }


}