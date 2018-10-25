<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/6/19
 * Time: 上午11:40
 */

namespace api\components\handler;


use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\DistrictCityAR;
use common\ActiveRecord\DistrictDistrictAR;
use common\ActiveRecord\DistrictProvinceAR;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\OrderItemAR;
use common\ActiveRecord\ProductAR;
use common\ActiveRecord\ProductSKUAR;
use common\components\handler\Handler;
use common\models\parts\MembraneOrder;
use common\models\parts\Order;
use common\models\parts\Product;
use custom\components\CustomUser;
use common\ActiveRecord\MembraneOrderAR;
use Yii;
use yii\db\Query;

class AliyunDataHandler extends Handler
{

    //获取供应商累计营业额前五
    public static function getSupplySaleFive(){
        $time = strtotime(date('Y-m-d'));
        return Yii::$app->RQ->AR(new OrderAR())->all([
            'select' => ['supply_user_id','sum(total_fee) as totalFee'],
            'where' => [
                'status' => self::getOrderStatus()
            ],
            'andWhere' => [
                '>=','pay_unixtime',$time
            ],
            'groupBy'=>'supply_user_id',
            'orderBy'=>'totalFee desc',
            'limit'=>5,
        ]);
    }

    //单小时销售冠军品牌名
    public static function getSupplyHourFirst(){
        $time = $_SERVER['REQUEST_TIME'] - 60*60;
        return Yii::$app->RQ->AR(new OrderAR())->scalar([
            'select' => ['supply_user_id','sum(total_fee) as totalFee'],
            'where' => [
                'status' => self::getOrderStatus()
            ],
            'andWhere' => [
                '>=','pay_unixtime',$time
            ],
            'groupBy'=>'supply_user_id',
            'orderBy'=>'totalFee desc',
        ]);
    }

    public static function getSumProduct(){
        $time = strtotime(date('Y-m-d'));
        $orderId = Yii::$app->RQ->AR(new OrderAR())->column([
            'select' => ['id'],
            'where' => [
                'status' => self::getOrderStatus()
            ],
            'andWhere' => [
                '>=','pay_unixtime',$time
            ]
        ]);

        return Yii::$app->RQ->AR(new OrderItemAR())->sum([
             'where'=>[
                 'order_id'=>$orderId,
             ],
            'andWhere'=>[
                'not in','product_sku_id',[
                    12860,
                    12861,
                    12862,
                    12863,
                    12864,
                    12865,
                    12866,
                    12867,
                    12868,
                    12869,
                    12870,
                    12871
                ]
            ]
        ],'count');



    }
    //获取下单排名
    public static function getOrderRank($limit = 13)
    {
         $time = strtotime(date('Y-m-d',$_SERVER['REQUEST_TIME']));
         return Yii::$app->RQ->AR(new OrderAR())->all([
            'select' => ['custom_user_id','sum(total_fee) as totalFee'],
            'where' => [
                'status' => self::getOrderStatus()
            ],
            'andWhere' => [
                '>=','pay_unixtime',$time
            ],
            'groupBy'=>'custom_user_id',
            'orderBy'=>'totalFee desc',
            'limit'=>$limit,
        ]);
    }

    /**
     *====================================================
     * 有效订单状态
     * @return array
     * @author shuang.li
     *====================================================
     */
    private static function getOrderStatus(){
        return [
            Order::STATUS_UNDELIVER,
            Order::STATUS_DELIVERED,
            Order::STATUS_CONFIRMED,
            Order::STATUS_CLOSED
        ];
    }

    /**
     *====================================================
     * @return array|\yii\db\ActiveRecord[]
     * @author shuang.li
     *====================================================
     */
    public static function getHotArea()
    {
        $time = strtotime(date('Y-m-d',$_SERVER['REQUEST_TIME']));
        return OrderAR::find()
                ->from(OrderAR::tableName().' O')
                ->select(['C.district_province_id', 'sum(O.total_fee) as totalFee '])
                ->leftJoin(CustomUserAR::tableName(). ' C', 'O.custom_user_id = C.id')
                ->where(['O.status' => self::getOrderStatus()])
                ->andWhere(['>=','pay_unixtime',$time])
                ->groupBy('C.district_province_id')
                ->orderBy('totalFee desc')
                ->asArray()
                ->all();
    }

    /**
     *====================================================
     * 获取各小时内的销售金额
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public static function getHourTotalFee(){
        $time = strtotime(date("Y-m-d",$_SERVER['REQUEST_TIME']));
        return Yii::$app->RQ->AR(new OrderAR())->all([
            'select'=>['sum(total_fee) as totalFee','DATE_FORMAT(pay_datetime,"%H") as hour'],
            'where'=>[
                'status'=>self::getOrderStatus()
            ],
            'andWhere' => [
                '>=','pay_unixtime',$time
            ],
            'groupBy'=>[
                'DATE_FORMAT(pay_datetime,"%H")'
            ],
            'orderBy'=> 'hour asc',

        ]);

    }


    /**
     * 获取飞线数据
     * @return array
     */
    public static function getFlyLine()
    {
        $time = time() - 30;
        $userId = OrderAR::find()
            ->select(['custom_user_id'])
            ->where(['>=', 'pay_unixtime', $time])
            ->andWhere(['in', 'status', static::getOrderStatus()])
            ->column();
        $userId = array_unique($userId);
        $provinces = CustomUserAR::find()
            ->select(['district_province_id'])
            ->where(['id' => $userId])
            ->column();
        $provinces = array_unique($provinces);
        $res = DistrictProvinceAR::find()
            ->select(['name', 'lat', 'lng'])
            ->where(['id' => $provinces])
            ->asArray()->all();
        return $res;
    }


    /**
     *====================================================
     * 获取二级区域销售额
     * @return array|\yii\db\ActiveRecord[]
     * @author shuang.li
     *====================================================
     */
    public static function getCityAmountAll(){
        //获取产生交易的二级区域订单数据
        $time = strtotime(date('Y-m-d',$_SERVER['REQUEST_TIME']));
         return OrderAR::find()
            ->from(OrderAR::tableName().' O')
            ->select(['C.district_city_id', 'sum(O.total_fee) as totalFee '])
            ->leftJoin(CustomUserAR::tableName(). ' C', 'O.custom_user_id = C.id')
            ->where(['O.status' => self::getOrderStatus()])
            ->andWhere(['>=','pay_unixtime',$time])
            ->groupBy('C.district_city_id')
            ->orderBy('totalFee desc')
            ->asArray()
            ->all();
    }

     /**
     * 当天订单城市与数量
     * @return array
     */
    public static function getTodayCityAndNum()
    {
        $date = date('Y-m-d');
        $data = OrderAR::find()
            ->from(OrderAR::tableName() . ' AS a')
            ->select(['c.district_city_id as id', 'sum(total_fee) AS num'])
            ->where(['>=', 'pay_datetime', $date])
            ->andWhere(['in', 'a.status', static::getOrderStatus()])
            ->leftJoin(CustomUserAR::tableName() . ' AS c', 'c.id = custom_user_id')
            ->groupBy('c.district_city_id')
            ->orderBy('num desc')
            ->limit(10)
            ->asArray()
            ->all();
        return $data;
    }

    /**
     * 当天订单地区与数量
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getTodayDistrictAndNum()
    {
        $date = date('Y-m-d');
        $data = OrderAR::find()
            ->from(OrderAR::tableName() . ' AS a')
            ->select(['c.district_district_id as id', 'sum(total_fee) AS num'])
            ->where(['>=', 'pay_datetime', $date])
            ->andWhere(['in', 'a.status', static::getOrderStatus()])
            ->leftJoin(CustomUserAR::tableName() . ' AS c', 'c.id = custom_user_id')
            ->groupBy('c.district_district_id')
            ->orderBy('num desc')
            ->limit(10)
            ->asArray()
            ->all();
        return $data;
    }

    /**
     * 当前小时销售额最高的二级区域
     * @return array
     */
    public static function getCityMaxSale()
    {
        $date = date('Y-m-d H');
        $data = OrderAR::find()
            ->from(OrderAR::tableName() . ' AS a')
            ->select(['sum(a.total_fee) as value', 'b.district_city_id as id'])
            ->where(['>=', 'pay_datetime', $date])
            ->andWhere(['a.status' => static::getOrderStatus()])
            ->groupBy('b.district_city_id')
            ->rightJoin(CustomUserAR::tableName() . ' AS b', 'b.id = a.custom_user_id')
            ->orderBy('value desc')
            ->limit(1)->asArray()->one();
        if($data){
            $name = Yii::$app->RQ->AR(new DistrictCityAR())->scalar([
                'select'    => ['name'],
                'where'     => ['id' => $data['id']],
                'limit'     => 1
            ]);
            $data['name'] = $name;
            unset($data['id']);
            $data['value'] = round($data['value'] / 100) / 100;
            return $data;
        }
        return [];
    }

    /**
     * 当前小时销售额最高的三级区域
     * @return array
     */
    public static function getCityDistrictSale()
    {
        $date = date('Y-m-d H');
        $data = OrderAR::find()
            ->from(OrderAR::tableName() . ' AS a')
            ->select(['sum(a.total_fee) as value', 'b.district_district_id as id'])
            ->where(['>=', 'pay_datetime', $date])
            ->andWhere(['a.status' => static::getOrderStatus()])
            ->groupBy('b.district_district_id')
            ->leftJoin(CustomUserAR::tableName() . ' AS b', 'b.id = a.custom_user_id')
            ->orderBy('value desc')
            ->limit(1)->asArray()->one();
        if($data){
            $name = Yii::$app->RQ->AR(new DistrictDistrictAR())->scalar([
                'select'    => ['name'],
                'where'     => ['id' => $data['id']],
                'limit'     => 1
            ]);
            $data['name'] = $name;
            unset($data['id']);
            $data['value'] = round($data['value'] / 100) / 100;
            return $data;
        }
        return [];
    }

    /**
     * 最新售出商品战报
     * @param int $num
     * @return array
     */
    public static function getHotProducts($num = 18)
    {
        $data = (new Query())->from(OrderItemAR::tableName() . ' AS a')
            ->select(['a.title as attribute', 'a.total_fee as pv', 'a.custom_user_id as id'])
            ->leftJoin(OrderAR::tableName() . ' AS b', 'a.order_id = b.id')
            ->orderBy('b.pay_datetime desc')
            ->limit($num)
            ->all();
        $ids = array_unique(array_column($data, 'id'));
        $users = Yii::$app->RQ->AR(new CustomUserAR())->all([
            'select'    => ['district_city_id', 'district_district_id', 'id'],
            'where'     => ['id' => $ids],
            'indexBy'   => 'id'
        ]);
        foreach ($users as $key=>$user){
            $name = Yii::$app->RQ->AR(new DistrictDistrictAR())->scalar([
                'select' => ['name'],
                'where'  => ['id' => $user['district_district_id']]
            ]);
            $name .= '-' . Yii::$app->RQ->AR(new DistrictCityAR())->scalar([
                    'select'    => ['name'],
                    'where' => ['id' => $user['district_city_id']]
                ]);
            $users[$key] = $name;
        }
        $res = [];
        foreach ($data as $datum){
            $res[] = [
                'attribute' => $datum['attribute'],
                'pv'        => $datum['pv'],
                'area'      => $users[$datum['id']]
            ];
        }
        return $res;
    }

    /**
     * 库存预警
     * @param $num
     * @return array
     */
    public static function getStockWarning($num = 12)
    {
        return (new Query())
            ->from(ProductSKUAR::tableName() . ' AS a')
            ->select(['b.title', 'sum(a.stock) as stock', 'b.id'])
            ->leftJoin(ProductAR::tableName() . ' AS b', 'b.id = a.product_id')
            ->groupBy('a.product_id')
            ->where(['sale_status'=>Product::SALE_STATUS_ONSALE])
            ->orderBy('stock')
            ->limit($num)
            ->all();
    }

    /**
     * 省累计销售
     * @param $date
     * @return array
     */
    public static function getProvinceSale($date)
    {
        return (new Query)
            ->from(DistrictProvinceAR::tableName() . ' AS a')
            ->select(['a.name as x', 'sum(c.total_fee) as y'])
            ->groupBy('a.id')
            ->leftJoin(CustomUserAR::tableName() . ' AS b', 'a.id = b.district_province_id')
            ->leftJoin(OrderAR::tableName() . ' AS c', 'c.custom_user_id = b.id')
            ->where(['>=', 'c.pay_datetime', $date])
            ->andWhere(['in', 'c.status', static::getOrderStatus()])
            ->orderBy('y desc')
            ->all();
    }

    public static function getProvinceSaleWithMembrane($date){
        return Yii::$app->db->createCommand("SELECT `province`.`name` AS `x`, sum(`u_fee`.`total_fee`) AS `y` FROM ((SELECT `order`.`custom_user_id` AS `custom_user_id`, `order`.`total_fee` AS `total_fee` FROM {{%order}} AS `order` WHERE `order`.`pay_datetime` >= :date AND `order`.`status` IN (1,2,3,5)) UNION ALL (SELECT `membrane`.`custom_user_id` AS `custom_user_id`, `membrane`.`total_fee` AS `total_fee` FROM {{%membrane_order}} AS `membrane` WHERE `membrane`.`pay_date` >= :date AND `membrane`.`status` IN (2,3,4))) AS `u_fee` INNER JOIN {{%custom_user}} AS `user` ON `u_fee`.`custom_user_id` = `user`.`id` INNER JOIN {{%district_province}} AS `province` ON `user`.`district_province_id` = `province`.`id` GROUP BY `province`.`name` ORDER BY `y` DESC", [':date' => $date])->queryAll();
    }

    /**
     * 有效的销售总额
     * @param $date
     * @return int
     */
    public static function getSaleTotalPrice($date)
    {
        $normal = Yii::$app->RQ->AR(new OrderAR())->sum([
            'where'     => ['>=', 'create_datetime', $date],
            'andWhere'  => ['status' => static::getOrderStatus() ]
        ], 'total_fee');

         $normal += Yii::$app->RQ->AR(new MembraneOrderAR)->sum([
                'where'     => ['>=', 'created_date', $date],
                'andWhere'  => ['status' => MembraneOrder::$validStatus]
            ], 'total_fee');

         return floatval($normal);
    }

    /**
     * 参与消费的门店数
     * @param $date
     * @return mixed
     */
    public static function getSumCustom($date)
    {
        return Yii::$app->RQ->AR(new OrderAR())->column([
            'select'    => ['custom_user_id'],
            'where'     => ['>=', 'pay_datetime', $date],
            'andWhere'  => ['status' => static::getOrderStatus()],
            'groupBy'   => 'custom_user_id'
        ]);
    }
}
