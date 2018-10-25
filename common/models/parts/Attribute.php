<?php
namespace common\models\parts;

use Yii;
use common\models\parts\basic\AttributeAbstract;
use common\models\RapidQuery;
use common\ActiveRecord\ProductSPUAttributeAR;
use common\ActiveRecord\ProductSPUOptionAR;
use yii\base\InvalidParamException;
use common\traits\ErrCallbackTrait;

class Attribute extends AttributeAbstract{

    use ErrCallbackTrait;

    //属性id
    public $id;
    //已选择的选项id
    public $selectedOption;

    //当前AR
    protected $AR;

    /**
     * 初始化属性对象
     *
     * 检测属性是否存在，$selectedOption可选
     */
    public function init()
    {
        $existId = (new RapidQuery(new ProductSPUAttributeAR))->exists([
            'where' => [
                'id' => $this->id,
            ],
        ]);
        if (is_null($this->selectedOption))
        {
            $existOption = true;
        }
        else
        {
            $existOption = (new RapidQuery(new ProductSPUOptionAR))->exists([
                'where' => [
                    'id' => $this->selectedOption,
                    'product_spu_attribute_id' => $this->id,
                ],
            ]);
        }
        if (!$existId or !$existOption)
        {
            throw new InvalidParamException;
        }
        else
        {
            $this->AR = Yii::$app->RQ->AR(ProductSPUAttributeAR::findOne(['id' => $this->id]));
        }
    }

    /**
     * inherit
     *
     * @return integer
     */
    public function getAttributes(){
        return $this->id;
    }

    /**
     * inherit
     *
     * @return array
     */
    public function getAttributesWithOptions(){
        return [
            'id' => $this->id,
            'name' => self::getAttributeName($this->id),
            'options' => self::getOptions($this->id),
            'selectedOption' => $this->selectedOption,
        ];
    }

    public function addOption(string $option, $return = 'throw'){
        if(empty($option))return $this->errCallback($return, 'string');
        if(Yii::$app->RQ->AR(new ProductSPUOptionAR)->exists([
            'where' => [
                'product_spu_attribute_id' => $this->id,
                'name' => $option,
                'display'=>ProductSPUOptionAR::DISPLAY,
            ],
            'limit' => 1,
        ]))return $this->errCallback($return, 'the option name is exist');
        return Yii::$app->RQ->AR(new ProductSPUOptionAR)->insert([
            'product_spu_attribute_id' => $this->id,
            'name' => $option,
        ], $return);
    }

    public function addOptions(array $options, $return = 'throw'){
        if(empty($options))return $this->errCallback($return, 'array');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($options as $option){
                $this->addOption($option);
            }
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return $this->errCallback($return, 'add multi options failed');
        }
    }


    //更新属性名称
    public function setName(string $name, $return = 'throw')
    {
        if ($name && $this->AR->update(['name' => $name])!== false)
        {
            return true;
        }
        return false;

    }

    /**
     * 格式化选项
     *
     * @return array
     */
    public function formatOptions(array $options){
        return array_map(function($option){
            if(is_array($option)){
                if(isset($option['name'])){
                    return [
                        $this->id,
                        $option['name'],
                        isset($option['sort']) ? $option['sort'] : ProductSPUOptionAR::DEFAULT_SORT
                    ];
                }else{
                    throw new InvalidParamException;
                }
            }else{
                return [
                    $this->id,
                    $option,
                    ProductSPUOptionAR::DEFAULT_SORT
                ];
            }
        }, $options);
    }
}
