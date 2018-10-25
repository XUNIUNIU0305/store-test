<?php
namespace mobile\modules\temp\models;

use common\ActiveRecord\ActivityGroupbuyAR;
use common\ActiveRecord\ActivityGroupbuyPriceAR;
use common\ActiveRecord\ActivityGroupbuyOrderAR;
use common\ActiveRecord\HomepageColumnItemAR;
use common\ActiveRecord\HomepageColumnAR;
use common\ActiveRecord\DistrictProvinceAR;
use common\ActiveRecord\ProductCategoryAR;
use common\models\parts\Product;
use custom\models\ProductModel;
use common\models\Model;
use console\models\groupbuy\GroupbuyOrder;
use admin\modules\activity\models\groupbuy\GroupbuyModel as AdminGroupbuyModel;
use Yii;

class GroupbuyModel extends Model
{
    const SCE_GET_ALL_GROUPBUY_SPECIFIC = 'get_specific';
    const SCE_GET_ALL_GROUPBUY          = 'get_groupbuy';
    const SCE_IS_GROUPBUY               = 'is_groupbuy';
    
    const TOP_LEVEL_DEFAULT = '其他';
    
    public $groupbuy_id;
    public $product_id;
    
    public function rules(){
        return [
            [
                ['groupbuy_id','product_id'],
                'required',
                'message'=>9001,
            ],
        ];
    }
    
    public function scenarios()
    {
        return [
            self::SCE_GET_ALL_GROUPBUY_SPECIFIC => ['groupbuy_id'],
            self::SCE_GET_ALL_GROUPBUY          => [],
            self::SCE_IS_GROUPBUY               => ['product_id'],
        ];
    }
    
    public function getGroupbuy()
    {
        $result = ActivityGroupbuyAR::find()->where(['status' => 1])->orderBy(['created_time' => SORT_DESC])->asArray()->all();

        if(is_array($result)) {
            foreach($result as $key => $id) {
                $groupBuyProduct = current((new AdminGroupbuyModel())->getGroupbuy($id['id'], true));
                $page[$key]['groupbuy_price']       = Yii::$app->user->getIsGuest() ? null : $this->getGroupbuyPrice($groupBuyProduct);
                $page[$key]['sales']                = $id['achieve_sales'];
                $page[$key]['product_id']           = $result[$key]['product_id'];
                $page[$key]['groupbuy_id']          = $groupBuyProduct['groupbuy_id'];
                $page[$key]['origin_price']         = $groupBuyProduct['product']['price']['min'];
                $page[$key]['title']                = $groupBuyProduct['product']['title'];
                $page[$key]['images']               = $groupBuyProduct['product']['big_images'];
                $page[$key]['top_level_category']   = $this->getTopLevelCategory($groupBuyProduct['product']['category']);
            }
        }

        return ['groupbuy' => $page];
    }
    
    public function getGroupbuyPrice($groupbuyProduct)
    {
        $groupbuy = ActivityGroupbuyAR::find()
                ->select(['id', 'achieve_sales', 'first_gradient_sales_goals', 'second_gradient_sales_goals', 'third_gradient_sales_goals'])
                ->where(['id' => $groupbuyProduct['groupbuy_id']])
                ->asArray()
                ->one();
        
        $grad = $this->getGradient(
                $groupbuy['achieve_sales'],
                $groupbuy['first_gradient_sales_goals'],
                $groupbuy['second_gradient_sales_goals'],
                $groupbuy['third_gradient_sales_goals']
        );
        
        if($grad === 0) {
            return $groupbuyProduct['product']['price']['min'];
        }
        
        $groupbuySKUs = ActivityGroupbuyPriceAR::findAll(['groupbuy_id' => $groupbuyProduct['groupbuy_id']]);
        
        if(!$groupbuySKUs) {
            $this->addError('getGroupbuyPrice', 14004);
            return false;
        }
        foreach($groupbuySKUs as $groupbuySKU) {
            $skus[] = $this->getGroupFinalPrice($groupbuySKU, $grad);
        }

        return $this->getMin($skus);
    }
    
    protected function getMin($data)
    {
        if(!is_array($data)) {
            return false;
        }
        foreach($data as $v) {
            if(!isset($min)) {
                $min = $v;
                continue;
            }
            if($v < $min) {
                $min = $v;
            }
        }
        return $min;
    }
    
    protected function getGradient($current, $first, $second, $third)
    {
        if($current  < $first) {
            return 0;
        } elseif($current  < $second) {
            return 1;
        } elseif($current >= $second && $current < $third) {
            return 2;
        } elseif($current >= $third) {
            return 3;
        }
        return 0;
    }
    
    protected function getGroupFinalPrice($groupbuySku, $gradient)
    {
        if(!isset($gradient)){
            return $groupbuySku->first_gradient_price;
        }
            
        switch ($gradient) {
            case 1:
                return $groupbuySku->first_gradient_price;
            case 2:
                return $groupbuySku->second_gradient_price;
            case 3:
                return $groupbuySku->third_gradient_price;
            default:
                return $groupbuySku->first_gradient_price;
        }
    }
    
    private function getTopLevelCategory($category)
    {
        $homepageColumnItem = HomepageColumnItemAR::getTableSchema()->fullName;
        $homepageColumn     = HomepageColumnAR::getTableSchema()->fullName;
        $toplevel = HomepageColumnItemAR::find()
                ->select(["$homepageColumn.name"])
                ->where(['cate_id' => $category])
                ->leftjoin($homepageColumn, "`$homepageColumnItem` . `column_id` = `$homepageColumn` . `id`")
                ->asArray()
                ->one();
        
        if($toplevel) {
            return $toplevel['name'];
        }
        return self::TOP_LEVEL_DEFAULT;
    }
    
    public function getSpecific($groupbuyId = null)
    {
        if(isset($groupbuyId)) {
            $this->groupbuy_id = $groupbuyId;
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
        $groupBuyProduct = current((new AdminGroupbuyModel())->getGroupbuy($this->groupbuy_id, true));
        $result['groupbuy_id'] = $this->groupbuy_id;
        $result['groupbuy_price'] = $this->getGroupbuyPrice($groupBuyProduct);
        $result['origin_price'] = $product['price']['min'];
        $result['sales'] = $groupbuy['achieve_sales'];
        $result['attributes'] = $sku['attributes'];
        $result['sku'] = $skuMerged;
        $result['first_gradient_sales_goals'] = $groupbuy['first_gradient_sales_goals'];
        $result['second_gradient_sales_goals'] = $groupbuy['second_gradient_sales_goals'];
        $result['third_gradient_sales_goals'] = $groupbuy['third_gradient_sales_goals'];
  
        return ['groupbuy' => $result];
    }
    
    private function getProduct($id)
    {
        $productModel = new ProductModel([
            'scenario' => ProductModel::SCE_GET_INFO,
            'attributes' => ['id' => $id],
        ]);
        $info = $productModel->process();
        
        return $info;
    }
    
    public function isGroupbuy()
    {
        $groupbuyProduct = ActivityGroupbuyAR::find()->where(['product_id' => $this->product_id, 'status' => 1])->one();

        if(!$groupbuyProduct) {
            return ['is_activity_product' => false, 'groupbuy_specific' => []];
        }
        
        return ['is_activity_product' => true, 'groupbuy_specific' => [$this->getSpecific($groupbuyProduct->id)]];
    }
}
