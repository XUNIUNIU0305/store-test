<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/27 0027
 * Time: 19:02
 */

namespace common\models\parts;


use common\ActiveRecord\MembraneOrderItemAR;
use common\ActiveRecord\MembraneOrderItemAttributeAR;
use yii\base\Model;

/**
 * Class MembraneOrderItemObject
 * @package common\models\parts
 */
class MembraneOrderItemObject extends Model
{
    public $param_id;

    public $product_id;

    public $name;

    public $price;

    public $orig_price;

    public $min_price;

    public $remark;

    public $image;

    public $params;

    public function rules()
    {
        return [
            [
                ['param_id', 'product_id'],
                'integer',
                'message' => 9001
            ],
            [
                ['price', 'orig_price', 'min_price'],
                'number',
                'message' => 9001
            ],
            [
                ['remark', 'name', 'image'],
                'string',
                'message' => 9001
            ],
            [
                ['param_id', 'price', 'name', 'orig_price', 'min_price', 'params', 'product_id'],
                'required',
                'message' => 9002
            ],
            [
                ['params'],
                'validateParams'
            ]
        ];
    }

    public function validateParams($attribute)
    {
        if(!is_array($this->$attribute))
            $this->addError($attribute, 9001);
    }

    public function save($id)
    {
        if($this->validate()){
            $entity = new MembraneOrderItemAR;
            $entity->setAttributes($this->getAttributes(), false);
            $entity->membrane_product_params_id = $this->param_id;
            $entity->membrane_order_id = $id;
            $entity->membrane_product_id = $this->product_id;
            $entity->insert();
            $this->saveAttributes($entity->id);
            return true;
        }
        return false;
    }

    public function saveAttributes($id)
    {
        \Yii::$app->db->createCommand()->batchInsert(MembraneOrderItemAttributeAR::tableName(), [
            'membrane_order_item_id',
            'membrane_item_block_id',
            'membrane_item_type_id',
            'membrane_item_block',
            'membrane_item_type'
        ], array_map(function($item) use($id){
            return [
                $id,
                $item['block_id'],
                $item['type_id'],
                $item['block'],
                $item['type']
            ];
        }, $this->params))->execute();
    }
}