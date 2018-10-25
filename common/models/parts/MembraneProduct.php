<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/25 0025
 * Time: 14:41
 */

namespace common\models\parts;


use common\ActiveRecord\MembraneProductAR;
use common\ActiveRecord\MembraneBlockAR;
use common\ActiveRecord\MembraneProductBlockAR;
use common\ActiveRecord\MembraneProductParamsAR;
use common\ActiveRecord\MembraneTypeAR;
use common\ActiveRecord\MembraneProductTypeAR;
use yii\base\InvalidConfigException;
use yii\base\Object;

class MembraneProduct extends Object
{
    const BLOCK_FRONTEND = 10;
    const BLOCK_BACKEND = 20;
    const BLOCK_LEFT_FRONTEND = 30;
    const BLOCK_RIGHT_FRONTEND = 40;
    const BLOCK_LEFT_BACKEND = 50;
    const BLOCK_RIGHT_BACKEND = 60;

    public static $blocksArray = [
        self::BLOCK_FRONTEND => '前挡',
        self::BLOCK_BACKEND => '后档',
        self::BLOCK_LEFT_FRONTEND => '左前挡',
        self::BLOCK_RIGHT_FRONTEND => '右前挡',
        self::BLOCK_LEFT_BACKEND => '左后挡',
        self::BLOCK_RIGHT_BACKEND => '右后档'
    ];

    public $id;

    /**
     * @var MembraneProductAR $AR
     */
    private $AR;

    public function init()
    {
        if(!$this->AR && $this->id)
            $this->AR = MembraneProductAR::findOne(['id' => $this->id]);
        if(!$this->AR instanceof MembraneProductAR)
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

    public function getImage()
    {
        return $this->AR->image;
    }

    public function getRemark()
    {
        return $this->AR->remark;
    }

    private $types;
    public function getTypes()
    {
        if($this->types === null){
            $typesRel = \Yii::$app->RQ->AR(new MembraneProductTypeAR)->column([
                'select' => ['membrane_type_id'],
                'where' => ['membrane_product_id' => $this->id]
            ]);
            $types = MembraneTypeAR::find()
                ->where(['id' => $typesRel])
                ->all();
            $this->types = array_map(function($type){
                return new MembraneType(['AR' => $type]);
            },$types);
        }
        return $this->types;
    }

    private $blocksLabel;
    public function getBlocksLabel()
    {
        if($this->blocksLabel === null){
            $types = $this->getTypes();
            $blocks = $this->getBlocks();
            foreach($blocks as $block){
                $tmp = [];
                foreach ($types as $type){
                    if($type->has($block['id']))
                        $tmp[] = ['name' => $type->name, 'id' => $type->id];
                }
                $this->blocksLabel[] = [
                    'label' => $block,
                    'options' => $tmp
                ];
            }
        }
        return $this->blocksLabel;
    }

    private $blocksRel;
    public function getBlocksRel()
    {
        if($this->blocksRel === null){
            $this->blocksRel = \Yii::$app->RQ->AR(new MembraneProductBlockAR())->column([
                'select' => ['membrane_block_id'],
                'where' => ['membrane_product_id' => $this->id]
            ]);
        }
        return $this->blocksRel;
    }

    private $blocks;
    public function getBlocks()
    {
        if($this->blocks === null){
            $rel = $this->getBlocksRel();
            $this->blocks = \Yii::$app->RQ->AR(new MembraneBlockAR())->all([
                'select' => ['name', 'id'],
                'where' => ['id' => $rel]
            ]);
        }
        return $this->blocks;
    }

    private $productParams;
    public function getProductParams()
    {
        if($this->productParams === null){
            $this->productParams = [];
            $params = MembraneProductParamsAR::find()
                ->where(['product_id' => $this->id])
                ->all();
            foreach ($params as $param){
                $this->productParams[] = new MembraneProductParams(['AR' => $param]);
            }
        }
        return $this->productParams;
    }
}