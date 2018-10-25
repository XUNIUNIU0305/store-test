<?php
namespace admin\modules\activity\models\groupbuy;

use common\ActiveRecord\ProductAR;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\CustomUserAR;
use common\ActiveRecord\ActivityGroupbuyAR;
use common\ActiveRecord\ActivityGroupbuyLogAR;
use common\ActiveRecord\ActivityGroupbuyOrderAR;
use common\ActiveRecord\ActivityGroupbuyPriceAR;
use common\ActiveRecord\DistrictProvinceAR;
use common\ActiveRecord\DistrictCityAR;
use common\ActiveRecord\DistrictDistrictAR;
use common\models\Model;
use custom\models\ProductModel;
use common\models\parts\Product;
use common\models\parts\supply\SupplyUser;
use common\components\handler\Handler;
use common\components\handler\ExcelHandler;
use yii\data\ActiveDataProvider;
use Yii;

class GroupbuyModel extends Model
{
    const SCE_GET_GROUPBUY_PRODUCT  = 'get_groupbuy_product';
    const SCE_GET_GROUPBUY          = 'get_groupbuy';
    const SCE_CREATE_GROUPBUY       = 'create_groupbuy';
    const SCE_GET_SKU               = 'get_sku';
    const SCE_EXPORT                = 'export';
    
    //pagination const
    const DEFAULT_COUNT_PER_PAGE = 10;
    const DEFAULT_PAGE_NUM = 1;
    
    //pagination
    public $current_page;
    public $page_size;
    
    public $name_or_id;
    public $product_id;
    public $groupbuy_id;
    public $groupbuy_name;
    public $first_gradient_sales_goals;
    public $second_gradient_sales_goals;
    public $third_gradient_sales_goals;
    public $first_gradient_price;
    public $second_gradient_price;
    public $third_gradient_price;
    
    public function scenarios()
    {
        return [
            self::SCE_GET_GROUPBUY          => ['groupbuy_id'],
            self::SCE_GET_GROUPBUY_PRODUCT  => ['current_page', 'page_size'],
            self::SCE_GET_GROUPBUY          => ['groupbuy_id'],
            self::SCE_GET_SKU               => ['product_id'],
            self::SCE_EXPORT                => [],
            self::SCE_CREATE_GROUPBUY       => [
                'product_id',
                'groupbuy_name',
                'first_gradient_sales_goals',
                'second_gradient_sales_goals',
                'third_gradient_sales_goals',
                'first_gradient_price',
                'second_gradient_price',
                'third_gradient_price',
            ],
        ];
    }


    public function rules()
    {
        return [
            [
                [
                    'name_or_id',
                    'product_id',
                    'groupbuy_id',
                    'groupbuy_name',
                    'first_gradient_sales_goals',
                    'second_gradient_sales_goals',
                    'third_gradient_sales_goals',
                    'first_gradient_price',
                    'second_gradient_price',
                    'third_gradient_price',
                ],
                'required',
                'message'=>9001,
            ],
            [
                'groupbuy_name',
                'string',
                'max' => 10,
                'tooLong' => 14016,
                'message' => 9002,
            ],            
            [
                [
                    'first_gradient_sales_goals',
                    'second_gradient_sales_goals',
                    'third_gradient_sales_goals',
                ],
                'integer',
                'message' => 14010,
            ],
            [
                [
                    'first_gradient_sales_goals',
                    'second_gradient_sales_goals',
                    'third_gradient_sales_goals',
                ],
                'compare',
                'operator' => '>=',
                'compareValue' => 0,
                'message' => 14015,
            ],
            [
                [
                    'first_gradient_price',
                    'second_gradient_price',
                    'third_gradient_price',
                ],
                'each',
                'rule' => [
                    'double',
                    'message' => 14012,
                ],
                'message' => 14013,
            ],
            [
                [
                    'first_gradient_price',
                    'second_gradient_price',
                    'third_gradient_price',
                ],
                'each',
                'rule' => [
                    'compare',
                    'operator' => '>=',
                    'compareValue' => 0,
                    'message' => 14014,
                ],
                'message' => 14013,
            ],
        ];
    }
    
    //获得团购列表
    public function getGroupbuyProduct()
    {
        if(!isset($this->current_page) || $this->current_page < 1) {
            $this->current_page = self::DEFAULT_PAGE_NUM;
        }
        
        if(!isset($this->page_size) || $this->page_size <1) {
            $this->page_size = self::DEFAULT_COUNT_PER_PAGE;
        }
        
        $result =  new ActiveDataProvider([
            'query' => ActivityGroupbuyAR::find()
                ->select([
                    'id',
                    'groupbuy_name',
                    'product_id',
                ])
                ->where([
                    'status' => 1,
                ])
                ->asArray(),
            'pagination' => [
                'page' => $this->current_page - 1,
                'pageSize' => $this->page_size,
            ],
            'sort' => [
                'defaultOrder' => [
                    "created_time" => SORT_DESC,
                ],
            ],
        ]);
        
        $productInfoResult = [];
        
        //获取商品信息
        foreach($result->models as $k => $v) {
            $product = $this->getProduct($v['product_id']);
            $productInfoResult[$k] = $v;
            $productInfoResult[$k]['title'] = $product['title'];
            $productInfoResult[$k]['min_price'] = $product['price']['min'];
            $productInfoResult[$k]['max_price'] = $product['price']['max'];
            $productInfoResult[$k]['images'] = $product['big_images'];
            $productInfoResult[$k]['stock'] = $this->getStock($product);
            unset($product);
        }

        return [
            'count'         => $result->count,
            'total_count'   => $result->totalCount,
            'groupbuy'      => $productInfoResult,
        ];
    }
    
    public function getGroupbuy($groupId = null, $full = false)
    {
        if(!is_null($groupId)) {
            $this->groupbuy_id = $groupId;
        }
        
        if(!$groupbuy = ActivityGroupbuyAR::findOne($this->groupbuy_id)) {
            $this->addError('getGroupbuy', 14004);
            return false;
        }
        
        $groupbuyPrice = ActivityGroupbuyPriceAR::find()
                ->where(['groupbuy_id' => $this->groupbuy_id])
                ->asArray()
                ->all();
        
        $product = $this->getProduct(current($groupbuyPrice)['product_id']);

        if(!$product) {
            $this->addError('getGroupbuy', 14002);
            return false;
        }
        
        $sku = $product['SKU'];
        $skuMerged = [];
        foreach($groupbuyPrice as $skuValue) {
            $skuSpecific = $sku['sku'];
            foreach($skuSpecific as $k => $v) {
                if($skuValue['product_sku_id'] == $v['id']) {
                    $v['first_gradient_price']  = $skuValue['first_gradient_price'];
                    $v['second_gradient_price'] = $skuValue['second_gradient_price'];
                    $v['third_gradient_price']  = $skuValue['third_gradient_price'];
                    $skuMerged[$k] = $v;
                }
            }
        }
        
        $groupbuy = $groupbuy->toArray();
        
        if($full) {
            $result['product'] = $product;
        }
        $result['groupbuy_id'] = $this->groupbuy_id;
        $result['attributes'] = $sku['attributes'];
        $result['sku'] = $skuMerged;
        $result['first_gradient_sales_goals'] = $groupbuy['first_gradient_sales_goals'];
        $result['second_gradient_sales_goals'] = $groupbuy['second_gradient_sales_goals'];
        $result['third_gradient_sales_goals'] = $groupbuy['third_gradient_sales_goals'];

        return [$result];
    }
    
    //return specific sku infomation of given id.
    public function getSku($id = null)
    {
        if($id !== null) {
            $this->product_id = $id;
        }
        
        $productId = $this->product_id;
        $product = $this->getProduct($productId);
        
        if(!$product) {
            $this->addError('getSku', 14002);
            return false;
        }
        
        return [$product['SKU']] ?? [];
        
    }

    public function export()
    {
        try {
            $cashbackOrders = ActivityGroupbuyOrderAR::find()->all();
            $excelRows = [];
            foreach($cashbackOrders as $cashbackOrder){
                $order = OrderAR::findOne($cashbackOrder->order_id);
                $custom = CustomUserAR::findOne($cashbackOrder->custom_user_id);
                if($custom) {
                    $province = DistrictProvinceAR::findOne($custom->district_province_id);
                    $city = DistrictCityAR::findOne($custom->district_city_id);
                    $district = DistrictDistrictAR::findOne($custom->district_district_id);
                    $loc = (isset($province->name) ? $province->name : '') 
                            . '/' . (isset($city->name) ? $city->name : '')
                            . '/' . (isset($district->name) ? $district->name : '');
                }
                $excelRows[] = [
                    $cashbackOrder->id ?? '未定义',
                    $custom->account ?? '未定义',
                    $loc ?? '未定义',
                    $order->order_number ?? '未定义',
                    $order->total_fee ?? '未定义',
                    $cashbackOrder->cash_back_amount ?? '未定义',
                    $order->coupon_rmb ?? '未定义',
                    $order->status ?? '未定义',
                    $order->create_datetime ?? '未定义',
                    $order->pay_datetime ?? '未定义',
                    $cashbackOrder->cash_back_datetime ?? '未定义',
                    $cashbackOrder->comment ?? '未定义',
                ];
            }
            ExcelHandler::output($excelRows, [
                '序号',
                '客户账号',
                '所在地区',
                '订单号',
                '订单实付',
                '拼团返现',
                '订单优惠减免',
                '订单状态',
                '创建时间',
                '付款时间',
                '返现时间',
                '备注',
            ], '拼团活动详情' . date('Y_m_d_H_i_s'));
        } catch(\Exception $ex) {
            $this->addError('export', 14011);
            return false;
        }
        return [];
    }
    
    
    private function getStock(array $product)
    {
        $sku = $product['SKU']['sku'];
        if(empty($sku)) {
            return 0;
        }
        
        $stock = 0;
        
        foreach($sku as $k => $v) {
            $stock += $v['stock'] ?? 0;
        }

        return $stock;
           
    }
    
    //return product asociate array of given id.
    private function getProduct($id)
    {
        $productModel = new ProductModel([
            'scenario' => ProductModel::SCE_GET_INFO,
            'attributes' => ['id' => $id],
        ]);
        $info = $productModel->process();
        
        return $info;
    }
    
    //create groupbuy
    public function createGroupbuy()
    {
        if(!$product = ProductAR::find()->where(['id' => $this->product_id])->one()) {
            $this->addError('createGroupbuy', 14002);
            return false;
        }
        
        if(ActivityGroupbuyAR::find()->where([
            'groupbuy_name' => $this->groupbuy_name,
            'status' => 1,
        ])->orWhere([
            'product_id' => $this->product_id,
            'status' => 1,
        ])->exists()) {
            $this->addError('createGroupbuy', 14006);
            return false;            
        }
        
        if(!$this->checkValue(
            $this->first_gradient_sales_goals,
            $this->second_gradient_sales_goals,
            $this->third_gradient_sales_goals
        )) {
            $this->addError('createGroupbuy', 14007);
            return false;
        }
        
        if(!$formatPrice = $this->checkArrayFormatAndValue(
            $this->first_gradient_price,
            $this->second_gradient_price,
            $this->third_gradient_price
        )) {
            $this->addError('createGroupbuy', 14008);
            return false;
        }
        
        if(!$this->checkSku($this->first_gradient_price, $this->getSku($this->product_id))) {
            if($this->getErrors()) {
                return false;
            }
            $this->addError('createGroupbuy', 14009);
            return false;
        }
        
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $activityGroupbuy = new ActivityGroupbuyAR();
            $activityGroupbuy->groupbuy_name = $this->groupbuy_name;
            $activityGroupbuy->product_id = $this->product_id;
            $activityGroupbuy->first_gradient_sales_goals = $this->first_gradient_sales_goals;
            $activityGroupbuy->second_gradient_sales_goals = $this->second_gradient_sales_goals;
            $activityGroupbuy->third_gradient_sales_goals = $this->third_gradient_sales_goals;
            $activityGroupbuy->created_time = time();
            $activityGroupbuy->save();
            $this->insertPrice($activityGroupbuy->id, $this->product_id, $formatPrice);
            $transaction->commit();
        } catch(\Exception $ex) {
            $transaction->rollback();
            $this->addError('createGroupbuy', 14000);
            return false;
        }
        
        return ['id' => $activityGroupbuy->id];
    }
    
    //insert groupbuy price
    private function insertPrice($groupbuyId, $productId, $formatPrice)
    {
        if(!isset($groupbuyId) || !isset($productId)) {
            throw new \Exception(sprintf("Groupbuy ID and Product ID should not be null, In %s.", __METHOD__));
        }
        $data = [];
        $loop = 0;
        foreach($formatPrice as $key => $prices) {
            $data[$loop]['product_id'] = $productId;
            $data[$loop]['groupbuy_id'] = $groupbuyId;
            $data[$loop]['product_sku_id'] = $key;
            $data[$loop]['first_gradient_price'] = $prices['first'];
            $data[$loop]['second_gradient_price'] = $prices['second'];
            $data[$loop]['third_gradient_price'] = $prices['third'];
            $data[$loop]['created_time'] = time();
            $loop++;
        }
        return Yii::$app
                ->db
                ->createCommand()
                ->batchInsert(ActivityGroupbuyPriceAR::getTableSchema()->fullName, array_keys(current($data)), $data)
                ->execute();

    }
    
    private function checkValue($first, $second, $third, $order = 'ASC')
    {
        if($first<0 || $second<0 || $third<0) {
            $this->addError('checkArrayFormatAndValue', 14010);
            return false;
        }
        if($order == 'ASC') {
            if($first > $second) {
                return false;
            }
            if($second > $third) {
                return false;
            }
        } else {
            if($third > $second) {
                return false;
            }
            if($second > $first) {
                return false;
            }
        }
        return true;
    }
    
    private function checkArrayFormatAndValue($first, $second, $third)
    {
        if(!is_array($first) || !is_array($second) || !is_array($third)) {
            return false;
        }
        
        $formatValue = [];
        
        foreach($first as $key => $firstValue) {
            if(!isset($second[$key]) || !isset($third[$key])) {
                return false;
            }
            $formatValue[$key]['first']     = $firstValue;
            $formatValue[$key]['second']    = $second[$key];
            $formatValue[$key]['third']     = $third[$key];
            if(!$this->checkValue($formatValue[$key]['first'], $formatValue[$key]['second'], $formatValue[$key]['third'], 'DESC')) {
                return false;
            }
        }
        
        return $formatValue;
    }
    
    private function checkSku(array $sku, array $template)
    {
        foreach(current($template)['sku'] as $v) {
            $templateSkuKey[$v['id']] = $v['price'];
        }

        foreach($templateSkuKey as $id => $originPrice) {
            if((float)$sku[$id] > (float)$originPrice) {
                $this->addError('checkSku', 14017);
                return false;
            }
        }
        return empty(array_diff(array_keys($sku), array_keys($templateSkuKey)));
    }
}
