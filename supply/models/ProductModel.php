<?php
namespace supply\models;

use Yii;
use common\models\Model;
use common\models\parts\Product;

class ProductModel extends Model{

    const SCE_GET_LIST = 'get_list';
    const SCE_MODIFY_SALE_STATUS = 'modify_sale_status';

    public $current_page;
    public $page_size;
    public $product_id;
    public $sale_status;

    public function scenarios(){
        return [
            self::SCE_GET_LIST => [
                'current_page',
                'page_size',
            ],
            self::SCE_MODIFY_SALE_STATUS => [
                'product_id',
                'sale_status',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['current_page'],
                'default',
                'value' => 1,
            ],
            [
                ['page_size'],
                'default',
                'value' => 10,
            ],
            [
                ['current_page', 'page_size', 'product_id', 'sale_status'],
                'required',
                'message' => 9001,
            ],
            [
                ['current_page', 'page_size'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['product_id'],
                'common\validators\product\IdValidator',
                'userId' => Yii::$app->user->id,
                'message' => 1071,
            ],
            [
                ['sale_status'],
                'common\validators\product\SaleStatusValidator',
                'productId' => $this->product_id,
                'originStatus' => [Product::SALE_STATUS_ONSALE, Product::SALE_STATUS_UNSOLD],
                'canModifyTo' => [Product::SALE_STATUS_ONSALE, Product::SALE_STATUS_UNSOLD],
                'message' => 1072,
            ],
        ];
    }

    /**
     * 获取商品列表
     *
     * @return array
     */
    public function getList(){
        if(!$this->validate())return false;
        $list = Yii::$app->SupplyUser->product->get([
            'page' => $this->current_page - 1,
            'pageSize' => $this->page_size,
            'provide' => [
                'product' => 'models',
                'count',
                'total_count' => 'totalCount',
            ],
            'object' => [
                'id',
                'title',
                'category',
                'price',
                'main_image' => 'mainImage',
                'sale_status' => 'saleStatus',
            ],
        ]);
        if($list['product']){
            $list['product'] = array_map(function($product){
                $product['id'] = intval($product['id']);
                $product['main_image'] = $product['main_image']->getPath();
                return $product;
            }, $list['product']);
        }
        return $list;
    }

    /**
     * 修改商品销售状态
     *
     * @return boolean
     */
    public function modifySaleStatus(){
        if(!$this->validate())return false;
        $product = new Product(['id' => $this->product_id]);
        if($product->setSaleStatus($this->sale_status) !== false){
            return true;
        }else{
            $this->addError('modifySaleStatus', 1073);
            return false;
        }
    }
}
