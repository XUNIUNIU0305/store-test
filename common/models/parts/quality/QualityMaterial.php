<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 18:04
 */

namespace common\models\parts\quality;


use common\ActiveRecord\QualityAttributeAR;
use common\ActiveRecord\QualityMaterialAR;

use yii\base\InvalidCallException;
use yii\base\Object;
use Yii;

class QualityMaterial extends  Object
{

    public $id;
    protected $AR;


    protected $defaultAttribute=[
        0=>'可见光透射率',
        1=>'紫外线阻隔率',
        2=>'红外线阻隔率',
        3=>'太阳能阻隔率',
    ];

    public function init(){
         if(!$this->id||!$this->AR=QualityMaterialAR::findOne($this->id))throw new InvalidCallException();
    }

    //获取部们名称
    public function getName(){
        return $this->AR->name;
    }


    //获取属性
    public function getAttribute(){
        if($list=Yii::$app->RQ->AR(new QualityAttributeAR())->all([
            'where'=>['quality_material_id'=>$this->id],
            'select'=>['name','star','value'],
        ])){
            return $list;
        }else{
            return array_map(function($item){
                return [
                    'name'=>$item,
                    'star'=>'',
                    'value'=>'',
                ];
            },$this->defaultAttribute);
        }



    }





}