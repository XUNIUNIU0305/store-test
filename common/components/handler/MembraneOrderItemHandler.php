<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/27 0027
 * Time: 19:01
 */

namespace common\components\handler;

use common\ActiveRecord\MembraneProductAR;
use common\ActiveRecord\MembraneProductBlockAR;
use common\ActiveRecord\MembraneProductParamsAR;
use common\ActiveRecord\MembraneProductTypeAR;
use common\models\parts\MembraneOrderItem;
use common\models\parts\MembraneOrderItemObject;
use common\ActiveRecord\MembraneTypeAR;
use common\ActiveRecord\MembraneBlockAR;
use yii\web\BadRequestHttpException;

class MembraneOrderItemHandler
{
    public static function parseItems(array $items)
    {
        $res = [];
        foreach ($items as $item){
            $attributes = $item['attributes'];
            foreach ($attributes as $k=>$attribute){
                $label = static::findBlock($attribute['label']);
                $value = static::findType($attribute['value']);
                $attributes[$k] = [
                    'block' => $label->name,
                    'block_id' => $label->id,
                    'type' => $value->name,
                    'type_id' => $value->id
                ];
            }
            $param = static::findParam($item['id']);
            $obj = new MembraneOrderItemObject;
            $obj->setAttributes($param->getAttributes());
            $obj->remark = $item['remark'] ?? '';
            $obj->params = $attributes;
            $obj->param_id = $param->id;
            $res[] = $obj;
        }
        return $res;
    }

    private static $blocks = [];
    private static function findBlock($id)
    {
        if(!isset(static::$blocks[$id])){
            if($block = MembraneBlockAR::findOne($id))
                static::$blocks[$id] = $block;
            else
                throw new BadRequestHttpException('无效档位');
        }
        return static::$blocks[$id];
    }

    private static $types = [];
    private static function findType($id)
    {
        if(!isset(static::$types[$id])){
            if($type = MembraneTypeAR::findOne($id))
                static::$types[$id] = $type;
            else
                throw new BadRequestHttpException('无效型号');
        }
        return static::$types[$id];
    }

    private static $params = [];
    private static function findParam($id)
    {
        if(!isset(static::$params[$id])){
            if($param = MembraneProductParamsAR::findOne($id))
                static::$params[$id] = $param;
            else
                throw new BadRequestHttpException('无效产品');
        }
        return static::$params[$id];
    }
}