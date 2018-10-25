<?php
namespace api\models;


use api\components\handler\AliyunDataHandler;
use common\ActiveRecord\DistrictCityAR;
use common\ActiveRecord\DistrictDistrictAR;
use common\models\Model;
use common\models\parts\custom\CustomUser;
use common\models\parts\district\City;
use common\models\parts\district\Province;
use common\models\parts\Supplier;
use Yii;
use yii\helpers\ArrayHelper;

class AliyunModel extends Model
{
    const SCE_ORDER_RANK = 'order_rank';
    const SCE_FLY_LINE = 'fly_line';
    const SCE_BREATH_BUBBLE = 'breath_bubble';
    const SCE_HOT_AREA = 'hot_area';
    const SCE_SALE_AMOUNT_SPEED = 'sale_amount_speed';
    const SCE_CITY_SALE_RANK = 'city_sale_rank';
    const SCE_DISTRICT_SALE_RANK = 'district_sale_rank';
    const SCE_CITY_MAX_SALE = 'city_max_sale';
    const SCE_DISTRICT_MAX_SALE = 'district_max_sale';
    const SCE_HOT_PRODUCT = 'hot_product';
    const SCE_STOCK_WARNING = 'stock_warning';
    const SCE_PROVINCE_SALE = 'province_sale';
    const SCE_PROVINCE_SALE_WITH_MEMBRANE = 'province_sale_with_membrane';
    const SCE_SALE_TOTAL_PRICE = 'sale_total_price';
    const SCE_SUM_CUSTOM = 'sum_custom';
    const SCE_SUM_PRODUCT = 'sum_product';
    const SCE_SUPPLY_HOUR_FIRST = 'supply_hour_first';
    const SCE_SUPPLY_SALE_FIVE = 'supply_sale_five';

    public function scenarios()
    {
        return [
            self::SCE_ORDER_RANK => [],
            self::SCE_FLY_LINE => [],
            self::SCE_BREATH_BUBBLE => [],
            self::SCE_HOT_AREA => [],
            self::SCE_SALE_AMOUNT_SPEED => [],
            self::SCE_CITY_SALE_RANK => [],
            self::SCE_DISTRICT_SALE_RANK => [],
            self::SCE_CITY_MAX_SALE=>[],
            self::SCE_DISTRICT_MAX_SALE=>[],
            self::SCE_HOT_PRODUCT=>[],
            self::SCE_STOCK_WARNING=>[],
            self::SCE_PROVINCE_SALE=>[],
            self::SCE_PROVINCE_SALE_WITH_MEMBRANE=>[],
            self::SCE_SALE_TOTAL_PRICE=>[],
            self::SCE_SUM_CUSTOM=>[],
            self::SCE_SUM_PRODUCT=>[],
            self::SCE_SUPPLY_HOUR_FIRST=>[],
            self::SCE_SUPPLY_SALE_FIVE=>[],
        ];
    }

    /**
     *====================================================
     * 获取飞线数据
     * @return array|mixed
     * @author shuang.li
     *====================================================
     */
    public function flyLine()
    {
        try{
            $data = AliyunDataHandler::getFlyLine();
            $res = [];
            foreach ($data as $item){
                $res[] = [
                    'from'      => (float)$item['lng'] . ',' . (float)$item['lat'],
                    'to'        => '121.472644,31.231706',
                    'fromInfo'  => $item['name'],
                    'toInfo'    => '上海'
                ];
            }
            $res = empty($res) ? [['from'=>'', 'to'=>'', 'fromInfo'=>'','toInfo'=>'']] : $res;
            return $res;
        } catch (\Exception $e){
            return false;
        }
    }


    /**
     * 城市销售额前8名
     * @return array|bool
     */
    public function citySaleRank(){
        try{
            $data = AliyunDataHandler::getTodayCityAndNum();
            $cityId = array_column($data, 'id');
            $citys = DistrictCityAR::find()
                ->select(['name', 'id'])
                ->where(['id' => $cityId])
                ->indexBy('id')
                ->column();
            $res = [];
            foreach ($data as $datum){
                $res[] = [
                    'x'     => $citys[$datum['id']],
                    'y'     => round($datum['num'] / 100) / 100,
                    's'     => 1
                ];
            }
            $res = empty($res) ? [['x' => '', 'y'=>'', 's'=>'']] : $res;
            return $res;
        } catch (\Exception $e){
            return false;
        }
    }


    /**
     * 区域销售额前8名
     * @return array|bool
     */
    public function districtSaleRank(){
        try{
            $data = AliyunDataHandler::getTodayDistrictAndNum();
            $id = array_column($data, 'id');
            $district = DistrictDistrictAR::find()
                ->select(['name', 'id'])
                ->where(['id' => $id])
                ->indexBy('id')
                ->column();
            $res = [];
            foreach ($data as $datum){
                if(isset($datum['id']) && isset($district[$datum['id']])){
                    $res[] = [
                        'x'     => $district[$datum['id']],
                        'y'     => round($datum['num'] / 100) / 100,
                        's'     => 1
                    ];
                }
                if(count($res) >= 8)break;
            }
            $res = empty($res) ? [['x' => '', 'y'=>'', 's'=>'']] : $res;
            return $res;
        } catch (\Exception $e){
            return false;
        }
    }


    /**
     *====================================================
     * 累计售出的商品数量
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function sumProduct(){
        return [
            [
                'name'=>'累计售出商品总数',
                'value'=>AliyunDataHandler::getSumProduct()
            ]
        ];
    }

    /**
     *====================================================
     * 单小时销售冠军  供应商
     * @return array|mixed
     * @author shuang.li
     *====================================================
     */
    public function supplyHourFirst(){
        $cache = Yii::$app->fCache;
        $supplyUserId = AliyunDataHandler::getSupplyHourFirst();
        $cacheSupplyUserId = $cache->get('api_supply_hour_first_supply_id');
        if($cacheSupplyUserId && $supplyUserId == $cacheSupplyUserId){
            return [Yii::$app->fCache->get('api_supply_hour_first')];
        }else{
            $cache->set('api_supply_hour_first_supply_id',$supplyUserId,300);
            $data = [
                'name'=>'单小时销售冠军',
                'value'=>(new Supplier(['id'=>$supplyUserId]))->brandName,
            ];
            $cache->set('api_supply_hour_first',$data,300);

            return [$data];
        }

    }


    /**
     *====================================================
     * 供应商累计销售额前五
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function supplySaleFive()
    {
        $supplyUserIdTotalFeeArr = AliyunDataHandler::getSupplySaleFive();
        //销售额最大供应商
        $supplySaleFive = array_map(function ($data)
        {
            return [
                'x' => (new Supplier(['id' => $data['supply_user_id']]))->brandName,
                'y' => $data['totalFee'] ,
            ];
        }, $supplyUserIdTotalFeeArr);
        return $supplySaleFive;
    }



    /**
     *====================================================
     * 获取呼吸气泡数据
     * @return array|mixed
     * @author shuang.li
     *====================================================
     */
    public function breathBubble(){
        $cache = Yii::$app->fCache;
        $citySaleAmount = AliyunDataHandler::getCityAmountAll();
        $cityIdTotalFeeArr =  array_column($citySaleAmount, 'totalFee', 'district_city_id');
        $keys = array_keys($cityIdTotalFeeArr);
        //获取城市销售额最高的前五名
        $fiveCityId = array_splice($keys,0,5);
        $cacheFiveCityId = $cache->get('api_breath_bubble_five_city_id');
        if ($cacheFiveCityId && !array_diff($fiveCityId, $cacheFiveCityId))
        {
            //不存在差异 直接从缓存获取数据
            return $cache->get('api_breath_bubble');
        }else
        {
            $cache->set('api_breath_bubble_five_city_id', $fiveCityId,300);
            $combineArr = array_combine($fiveCityId, array_slice([10, 8, 6, 4, 2],0,count($fiveCityId)));
            $temp = [];$breathBubble = [];
            foreach ($combineArr as $cityId => $number)
            {
                $city = new City(['cityId' => $cityId]);
                $temp []= [
                    'lat' => $city->lat,
                    'lng' => $city->lng,
                    'value' => $number,
                    'type' => 2,
                ];
            }

            if ($keys){
                $breathBubble = array_map(function ($id)
                {
                    $city = new City(['cityId' => $id]);
                    return [
                        'lat' => $city->lat,
                        'lng' => $city->lng,
                        'value' => 1,
                        'type' => 1,
                    ];
                }, $keys);
            }
            $breathBubble = array_merge($temp, $breathBubble);
            //缓存呼吸气泡
            $cache->set('api_breath_bubble', $breathBubble,300);
            return $breathBubble;

        }
    }


    /**
     *====================================================
     * 销售额增速
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function saleAmountSpeed(){

        //获取各小时内总销售金额
        $hourSaleAmountSpeed = AliyunDataHandler::getHourTotalFee();
        $hour24 = range(0,abs(date('H')));
        //没有销售额的时间点
        $diffArr = array_diff($hour24,array_map(function($v){
            return (int)$v;
        },array_column($hourSaleAmountSpeed,'hour')));
        $temp = [];
        if ($diffArr){
            $temp =  array_map(function($data){
                return [
                    'x'=>bcadd($data,1),
                    'y'=>0,
                    's'=>1
                ];
            },$diffArr);
        }
         $saleSpeed = array_map(function($data){
            return [
                'x'=>bcadd($data['hour'],1),
                'y'=>$data['totalFee'],
                's'=>1
            ];
        },$hourSaleAmountSpeed);
        $sortArr = array_merge($saleSpeed,$temp);
        ArrayHelper::multisort($sortArr,'x');
        return $sortArr;
     }

    /**
     *====================================================
     * 获取热力区域数据
     * @return array|mixed
     * @author shuang.li
     *====================================================
     */
    public function hotArea(){
        $cache = Yii::$app->fCache;
        $provinceHotArea = AliyunDataHandler::getHotArea();
        $hotAreaProvinceIdAndTotalFee =  array_column($provinceHotArea, 'totalFee', 'district_province_id');
        //最大销售额省份
        $maxProvince = current($hotAreaProvinceIdAndTotalFee);

        $cacheHotAreaProvinceIdAndTotalFee = $cache->get('api_province_id_totalFee');
        if ($cacheHotAreaProvinceIdAndTotalFee && !array_diff_assoc($cacheHotAreaProvinceIdAndTotalFee, $hotAreaProvinceIdAndTotalFee))
        {
            //不存在差异 直接从缓存获取数据
            return $cache->get('api_hot_area');
        }else{
            $cache->set('api_province_id_totalFee',$hotAreaProvinceIdAndTotalFee,300);
            $hotArea = array_map(function($data)use($maxProvince) {
                return [
                    'id'=> (new Province(['provinceId'=>$data['district_province_id']]))->adCode,
                    'value'=>bcdiv($data['totalFee'],$maxProvince,10),
                ];
            },$provinceHotArea);

            $cache->set('api_hot_area',$hotArea,300);
            return $hotArea;
        }

    }

    /**
     *====================================================
     * 获取用户当前订单金额排名
     * @return array|mixed
     * @author shuang.li
     *====================================================
     */
    public function orderRank()
    {
        $cache = Yii::$app->fCache;
        $customUserData = AliyunDataHandler::getOrderRank(65);
        $customUserIdAndTotalFee = array_column($customUserData, 'totalFee', 'custom_user_id');
        $cacheCustomUserIdAndTotalFee = $cache->get('api_custom_user_id_totalFee');
        if ($cacheCustomUserIdAndTotalFee && !array_diff_assoc($cacheCustomUserIdAndTotalFee, $customUserIdAndTotalFee))
        {
            //不存在差异 直接从缓存获取数据
            return $cache->get('custom_order_rank');
        }
        else
        {
            $cache->set('api_custom_user_id_totalFee', $customUserIdAndTotalFee,300);
            //获取用户信息
            $customOrderRank = array_map(function ($data)
            {
                $customUser = new CustomUser(['id' => $data['custom_user_id']]);
                return [
                    'value' => $data['totalFee'],
                    'content' =>
                        ($customUser->defaultAddress ? $customUser->defaultAddress->consignee : ''). ': ' .
                        ($customUser->province ? $customUser->province->name : '' ). '-' .
                        ($customUser->city ? $customUser->city->name : '') . '-' .
                        ($customUser->district ? $customUser->district->name : ''),
                ];
            }, $customUserData);

            //缓存下单排名
            $cache->set('custom_order_rank', $customOrderRank,300);
            return $customOrderRank;

        }
    }

    /**
     * 当前小时销售额最高的二级区域
     * @return array
     */
    public function cityMaxSale()
    {
        $res = AliyunDataHandler::getCityMaxSale();
        $res = empty($res) ? ['value'=>'', 'name'=> ''] : $res;
        return [$res];
    }

    /**
     * 当前小时销售额最高的三级区域
     * @return array
     */
    public function districtMaxSale()
    {
        $res = AliyunDataHandler::getCityDistrictSale();
        $res = empty($res) ? ['value'=>'', 'name'=> '']: $res;
        return [$res];
    }

    /**
     * 最新售出商品战报
     * @return array
     */
    public function hotProduct()
    {
        $res = AliyunDataHandler::getHotProducts();
        $res = empty($res) ? [["attribute"=>"","pv"=>0,"area"=>""]]: $res;
        return $res;
    }

    /**
     * 库存预警
     * @return array
     */
    public function stockWarning()
    {
        $data = AliyunDataHandler::getStockWarning();
        $res = [];
        foreach ($data as $datum){
            $res[] = [
                'area'  => $datum['title'],
                'pv'    => $datum['stock'],
                'attribute' => ''
            ];
        }
        return $res;
    }

    /**
     * 省累计销售
     * @return array
     */
    public function provinceSale()
    {
        $date = date('Y-m-d');
        $res = AliyunDataHandler::getProvinceSale($date);
        foreach ($res as $key=>$val){
            $val['y'] = round($val['y'] / 100) /100;
            $res[$key] = $val;
        }
        $res = empty($res) ? [["x"=>"","y"=>0]] : $res;
        return $res;
    }

    public function provinceSaleWithMembrane()
    {
        $date = date('Y-m-d');
        $res = AliyunDataHandler::getProvinceSaleWithMembrane($date);
        foreach ($res as $key=>$val){
            $val['y'] = round($val['y'] / 100) /100;
            $res[$key] = $val;
        }
        $res = empty($res) ? [["x"=>"","y"=>0]] : $res;
        return $res;
    }

    /**
     * 当日有效的销售总额
     * @return array
     */
    public function saleTotalPrice()
    {
        $date = date('Y-m-d');
        $sum = AliyunDataHandler::getSaleTotalPrice($date);
        return [
            [
                //'name'  => '当日有效的销售总额',
                'name' => '',
                'value' => $sum
            ]
        ];
    }

    /**
     * 当日累计参与消费的门店数
     * @return array
     */
    public function sumCustom()
    {
        $date = date('Y-m-d');
        $sum = AliyunDataHandler::getSumCustom($date);
        return [
            [
                'name'  => '参与消费门店数',
                'value' => count($sum)
            ]
        ];
    }
}
