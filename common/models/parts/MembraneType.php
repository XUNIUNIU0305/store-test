<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/26 0026
 * Time: 15:08
 */

namespace common\models\parts;


use common\ActiveRecord\MembraneTypeAR;
use common\ActiveRecord\MembraneTypeBlockAR;
use yii\base\InvalidConfigException;
use yii\base\Object;

class MembraneType extends Object
{
    public $id;

    /**
     * @var MembraneTypeAR $AR
     */
    private $AR;

    public function init()
    {
        if(!$this->AR && $this->id)
            $this->AR = MembraneTypeAR::findOne($this->id);
        if(!$this->AR instanceof MembraneTypeAR)
            throw new InvalidConfigException;
        $this->id = $this->AR->id;
    }

    public function setAR($ar)
    {
        $this->AR = $ar;
    }
    /**
     * @param $block
     * @return bool
     */
    public function has($block)
    {
        return in_array($block, $this->getBlocksRel());
    }

    private $blocksRel;
    public function getBlocksRel()
    {
        if($this->blocksRel === null){
            $this->blocksRel = \Yii::$app->RQ->AR(new MembraneTypeBlockAR())->column([
                'select' => ['membrane_block_id'],
                'where' => ['membrane_type_id' => $this->id]
            ]);
        }
        return $this->blocksRel;
    }

    public function getName()
    {
        return $this->AR->name;
    }
}