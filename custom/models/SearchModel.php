<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/9/12
 * Time: 下午4:52
 */

namespace custom\models;

use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\Product;
use common\models\parts\ProductCategory;
use common\models\parts\Supplier;
use custom\components\handler\SearchHandler;
use custom\models\parts\search\CategoryAttributeObserver;
use custom\models\parts\search\CategoryKeywordObserver;
use custom\models\parts\search\CategoryNameObserver;
use custom\models\parts\search\CountKeywordObserver;
use custom\models\parts\search\GoodKeywordObserver;
use custom\models\parts\search\GoodNamePointObserver;
use custom\models\parts\search\SearchObject;
use Yii;
class SearchModel extends Model
{
    const SCE_RESULT = 'get_result';
    const SCE_CATEGORY_ATTRIBUTE = 'category_attribute';
    const SCE_CATEGORY_GOODS = 'category_goods';

    public $keyword;

    //三级分类id
    public $end_category_id;

    //0:价格  1：销量
    public $order_by;

    //商品
    public $option_id,$current_page,$page_size;

    public function scenarios()
    {
        return [
            self::SCE_RESULT => ['keyword'],
            self::SCE_CATEGORY_ATTRIBUTE => ['end_category_id'],
            self::SCE_CATEGORY_GOODS => ['option_id','current_page','page_size','end_category_id','order_by'],
        ];
    }

    public function rules()
    {
       return [
           [
               ['order_by'],
               'default',
               'value'=>[],
           ],
           [
               ['order_by'],
               'common\validators\custom\OrderByValidator',
               'message'=>3369,
           ],
           [
               ['current_page'],
               'default',
               'value'=>1,
           ],
           [
               ['page_size'],
               'default',
               'value'=>10,
           ],
           [
               ['keyword','current_page','page_size','end_category_id'],
               'required',
               'message'=>9001,
           ],
           [
               ['keyword'],
               'trim',
           ],
           [
               ['end_category_id'],
               'common\validators\category\EndIdValidator',
               'message' => 5061,
           ],

           [
               ['option_id'],
               'common\validators\custom\OptionIdValidator',
               'message'=>3352
           ]
       ];
    }


    public function getResult(){
        try {
            $search = new SearchObject(['keyword'=>$this->keyword]);

            //绑定观察者
            $goodKeyword = new GoodKeywordObserver();
            $categoryKeyword = new CategoryKeywordObserver();
            $goodNamePoint = new GoodNamePointObserver();
            $categoryName = new CategoryNameObserver();
            $categoryAttribute = new CategoryAttributeObserver();
            $countKeyword =  new CountKeywordObserver();
            $search->attach($goodKeyword);
            $search->attach($categoryKeyword);
            $search->attach($goodNamePoint);
            $search->attach($categoryName);
            $search->attach($categoryAttribute);
            $search->attach($countKeyword);
            $search->notify();
            $result = $search->getAnalysisResult();

            return [
                'brand'=>$result['brand_id'] ? array_map(function($brandId){
                    return Handler::getMultiAttributes(new Supplier(['id'=>$brandId]),[
                        'id',
                        'brand_name'=>'brandName',
                        'header_img'=>'headerImg',
                    ]);
                },$result['brand_id']) : '',
                'category'=>$result['category_id'] ? array_map(function($categoryId){
                    return Handler::getMultiAttributes(new ProductCategory(['id'=>$categoryId]),[
                        'id',
                        'title'
                    ]);
                },$result['category_id']) : '',
                'goods'=>$result['good_id'] ? array_map(function($goodId){
                    return Handler::getMultiAttributes(new Product(['id'=>$goodId]),[
                        'id',
                        'title',
                        'description',
                        'big_images'=>'bigImages',
                        'main_image'=>'mainImage',
                        'category'=>'categoryObj',
                        'price'=>Yii::$app->user->isGuest ? 'guidancePrice': 'price',
                        '_func'=>[
                            'bigImages' => function($image){
                                return $image->path;
                            },
                            'mainImage' => function($image){
                                return $image->path;
                            },
                            'categoryObj'=>function($category){
                                return $category->title;
                            }
                        ]
                    ]);
                },$result['good_id']) : '',
            ];
        }catch (\Exception $exception) {
            return [];
        }


    }

    public function categoryAttribute(){
        $model = new ProductCategory(['id'=>$this->end_category_id]);
        $attribute = $model->getAttributes();
        return  $attribute->getAttributesWithOptions() ? : [];
    }


    public function categoryGoods(){
        try {
            $data = SearchHandler::getProductIdByOptionId($this->end_category_id,$this->option_id,$this->current_page,$this->page_size,$this->order_by);
            $productIds = array_column($data->models, 'id');
            return [
                'count' => $data->count,
                'total_count' => $data->totalCount,
                'codes' => array_map(function($id){
                    return Handler::getMultiAttributes(new Product(['id'=>$id]),[
                        'id',
                        'title',
                        'description',
                        'big_images'=>'bigImages',
                        'main_image'=>'mainImage',
                        'category'=>'categoryObj',
                        'price'=>Yii::$app->user->isGuest ? 'guidancePrice': 'price',
                        '_func'=>[
                            'bigImages' => function($image){
                                return $image->path;
                            },
                            'mainImage' => function($image){
                                return $image->path;
                            },
                            'categoryObj'=>function($category){
                                return $category->title;
                            }
                        ]
                    ]);
                }, $productIds),
            ];

        }catch (\Exception $exception){
            return [];
        }
    }
}
