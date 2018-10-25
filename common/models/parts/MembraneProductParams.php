<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-16
 * Time: 下午3:29
 */

namespace common\models\parts;

use yii\base\InvalidConfigException;
use yii\base\Object;
use common\ActiveRecord\MembraneProductParamsAR;

class MembraneProductParams extends Object
{
    /**
     * @var MembraneProductParamsAR
     */
    private $AR;

    public $id;

    public function init()
    {
        if(!$this->AR && $this->id)
            $this->AR = MembraneProductParamsAR::findOne($this->id);
        if(!$this->AR instanceof MembraneProductParamsAR)
            throw new InvalidConfigException('参数错误');
        $this->id = $this->AR->id;
    }

    public function setAR($ar)
    {
        $this->AR = $ar;
    }

    public function getName()
    {
        return $this->AR->name;
    }

    public function getPrice()
    {
        return $this->AR->price;
    }

    public function getOrigPrice()
    {
        return $this->AR->orig_price;
    }

    public function getMinPrice()
    {
        return $this->AR->min_price;
    }

    public function getCoefficient()
    {
        return sprintf('%.2f', $this->AR->coefficient);
    }
}