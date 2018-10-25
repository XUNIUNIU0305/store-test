<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/26 0026
 * Time: 9:40
 */

namespace common\models\parts;


use common\ActiveRecord\MembraneOrderItemAR;
use common\ActiveRecord\MembraneOrderItemAttributeAR;
use yii\base\InvalidConfigException;
use yii\base\Object;

class MembraneOrderItem extends Object
{
    public $id;

    /**
     * @var MembraneOrderItemAR $AR
     */
    private $AR;

    public function init()
    {
        if(!$this->AR && $this->id)
            $this->AR = MembraneOrderItemAR::findOne($this->id);
        if(!$this->AR instanceof MembraneOrderItemAR)
            throw new InvalidConfigException();
        $this->id = $this->AR->id;
    }

    public function setAR($ar)
    {
        $this->AR = $ar;
    }

    public function getPrice()
    {
        return $this->AR->price;
    }

    public function getName()
    {
        return $this->AR->name;
    }

    public function getRemark()
    {
        return $this->AR->remark;
    }

    public function getImage()
    {
        return $this->AR->image;
    }

    private $attributes;
    public function getAttributes()
    {
        if($this->attributes === null){
            $this->attributes = MembraneOrderItemAttributeAR::find()
                ->where(['membrane_order_item_id' => $this->id])->all();
        }
        return $this->attributes;
    }

    public function getMembraneProductId(){
        return $this->AR->membrane_product_id;
    }
}
