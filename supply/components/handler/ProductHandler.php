<?php
namespace supply\components\handler;

use Yii;
use common\models\parts\Product;
use common\ActiveRecord\ProductAR;
use common\models\RapidQuery;
use yii\data\ActiveDataProvider;

class ProductHandler extends \common\components\handler\ProductHandler{

    /**
     * 生成商品对象
     *
     * @param array $param 数据库product表需保存的数据
     *
     * @return Object
     */
    public static function generate($param){
        if(!(new RapidQuery(new ProductAR))->insert($param))return false;
        return new Product(['id' => Yii::$app->db->getLastInsertID()]);
    }

    /**
     * 获取商品
     *
     * @param array $params = [
     *     'where' => array product表筛选条件
     *     'filterWhere' => array product表筛选条件
     *     'page' => integer 当前页码 第一页 = 0
     *     'pageSize' => integer 每页显示数量 缺省不分页
     *     'sort' => array 数据排序 配置详见\yii\data\Sort 缺省不排序
     *     'provide' => false|array 需要提供的数据 false => \yii\data\ActiveDataProvider本身, array => \yii\data\ActiveDataProvider::attributes 键值=需要返回的属性，键名=属性别名
     *     'object' => boolean|array 需要返回的商品数据 false => 返回商品id的数组, true => 返回\common\models\parts\Product本身, array => \common\models\parts\Product::attributes 键值=需要返回的属性，键名=属性别名
     * ]
     *
     * @return array|object
     */
    public function get($params){
        $where = [];
        $filterWhere = [];
        $page = 0;
        $pageSize = false;
        $sort = false;
        $provide = false;
        $object = false;
        if(is_array($params))extract($params, EXTR_IF_EXISTS);
        $products = new ActiveDataProvider([
            'query' => ProductAR::find()->select(['id'])->where(['supply_user_id' => Yii::$app->user->id])->andWhere($where)->filterWhere($filterWhere)->asArray(),
            'pagination' => $pageSize ? [
                'pageSize' => $pageSize,
                'page' => $page,
            ] : false,
            'sort' => $sort,
        ]);
        if(!is_array($provide))return $products;
        if($object){
            $data = array_map(function($product)use($object){
                $productObj = new Product(['id' => $product['id']]);
                if(is_array($object)){
                    $obj = [];
                    foreach($object as $key => $attr){
                        $obj[is_int($key) ? $attr : $key] = $productObj->$attr;
                    }
                    return $obj;
                }else{
                    return $productObj;
                }
            }, $products->getModels());
        }else{
            $data = $products->getModels();
        }
        $result = [];
        foreach($provide as $key => $attr){
            $result[is_int($key) ? $attr : $key] = strtolower($attr) == 'models' ? $data : $products->$attr;
        }
        return $result;
    }
}
