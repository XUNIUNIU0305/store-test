<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/5/8
 * Time: 上午10:04
 */

namespace custom\models;


use common\ActiveRecord\SupplyUserAR;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\Product;
use common\models\parts\supply\SupplyShop;
use yii\helpers\ArrayHelper;
use Yii;

class ShopModel extends Model
{
    const SCE_SHOP_LIST = 'get_list';
    const SCE_SHOP_LIST_BY_CONDITION = 'get_list_by_condition';

    public $supply_user_id;
    public $current_page;
    public $page_size;
    public $condition; // sales, price
    public $order; // 0：降序 1：升序

    public function scenarios()
    {
        return [
            self::SCE_SHOP_LIST =>['supply_user_id','current_page','page_size'],
            self::SCE_SHOP_LIST_BY_CONDITION => ['supply_user_id', 'current_page', 'page_size', 'condition', 'order'],
        ];
    }

    public function rules()
    {
        return [
            [
               ['current_page'],
                'default',
                'value'=>1
            ],
            [
                ['page_size'],
                'default',
                'value'=>10
            ],
            [
                ['condition'],
                'in',
                'range' => ['sales', 'price'],
                'message' => 9002,
            ],
            [
                ['order'],
                'in',
                'range' => [0, 1],
                'message' => 9002,
            ],
            [
                ['supply_user_id','current_page','page_size','condition','order'],
                'required',
                'message'=>9001,
            ],
            [
                ['supply_user_id'],
                'exist',
                'targetClass'=>SupplyUserAR::className(),
                'targetAttribute'=>['supply_user_id'=>'id'],
                'message'=>10005,
            ]
        ];
    }

    public function getList(){
        try
        {
            $shop = new SupplyShop(['id' => $this->supply_user_id]);
            $data = $shop->getProduct($this->current_page,$this->page_size);
            $products = array_map(function ($id)
            {
                return Handler::getMultiAttributes(new Product(['id' => $id]), [
                    'id',
                    'title',
                    'description',
                    'detail',
                    'price' => Yii::$app->user->isGuest ? 'guidancePrice' : 'price',
                    'mainImage',
                    '_func' => [
                        'mainImage' => function ($image)
                        {
                            return $image->path;
                        },
                    ],
                ]);
            },  ArrayHelper::getColumn($data->models,'id'));
            return ['products' => $products,'count' => $data->count, 'total_count' => $data->totalCount,];
        }
        catch (\Exception $e)
        {
            return [];
        }
    }

    public function getListByCondition()
    {
        try {
            $shop = new SupplyShop(['id' => $this->supply_user_id]);
            $data = $shop->getProductByConditon($this->current_page, $this->page_size, $this->condition, (int)$this->order);
            $products = array_map(function ($id) {
                return Handler::getMultiAttributes(new Product(['id' => $id]), [
                    'id',
                    'title',
                    'description',
                    'price' => Yii::$app->user->isGuest ? 'guidancePrice' : 'price',
                    'mainImage',
                    '_func' => [
                        'mainImage' => function ($image) {
                            return $image->path;
                        },
                    ],
                ]);
            },  ArrayHelper::getColumn($data->models,'id'));

            if ($this->condition === 'price') {
                $sortArray = [];
                foreach ($products as $key => $product) {
                    $sortArray[$key]['price'] = $product['price']['min'];
                    $sortArray[$key]['id'] = $product['id'];
                }

                if ((int)$this->order === 0) {
                    $tmp1 = $this->arraySort($sortArray, 'price', SORT_DESC);
                } else {
                    $tmp1 = $this->arraySort($sortArray, 'price', SORT_ASC);
                }
                $arr = [];
                foreach ($tmp1 as $tmp) {
                    $arr[] = $tmp['id'];
                }

                $result = [];
                foreach ($arr as $index) {
                    foreach ($products as $product) {
                        if ($product['id'] == $index) {
                            $result[] = $product;
                        }
                    }
                }
            }
            return ['products' => (isset($result) ? $result : $products)];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * 二维数组根据某个字段排序
     * @param array $array 要排序的数组
     * @param string $keys   要排序的键字段
     * @param string $sort  排序类型  SORT_ASC     SORT_DESC
     * @return array 排序后的数组
     */
    function arraySort($array, $keys, $sort = 'SORT_DESC') {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }


}