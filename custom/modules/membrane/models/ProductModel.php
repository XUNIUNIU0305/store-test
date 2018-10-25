<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/25 0025
 * Time: 15:28
 */

namespace custom\modules\membrane\models;


use common\components\handler\Handler;
use common\components\handler\MembraneProductHandler;
use common\models\Model;

class ProductModel extends Model
{
    const SCE_PRODUCTS = 'get_products';

    public function scenarios()
    {
        return [
            self::SCE_PRODUCTS => []
        ];
    }

    /**
     * @return bool
     * 获取产品
     */
    public function getProducts()
    {
        try {
            $res = MembraneProductHandler::findAll();
            return array_map(function($item){
                return Handler::getMultiAttributes($item, [
                    'image',
                    'blocks' => 'blocksLabel',
                    'params' => 'productParams',
                    'remark',
                    '_func' => [
                        'productParams' => function($params) {
                            return array_map(function($param){
                                return [
                                    'id' => $param->id,
                                    'name' => $param->name,
                                    'coefficient' => $param->coefficient,
                                    'price' => $param->price,
                                    'orig_price' => $param->origPrice,
                                    'min_price' => $param->minPrice
                                ];
                            }, $params);
                        }
                    ]
                ]);
            }, $res);
        } catch (\Exception $e){
            $this->addError('', 3350);
            return false;
        }
    }
}