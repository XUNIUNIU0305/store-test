<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 18:04
 */

namespace common\models\parts\quality;


use common\ActiveRecord\QualityPackageAR;

use common\ActiveRecord\QualityPackagePlaceAR;
use common\ActiveRecord\QualityPriceAR;
use common\components\handler\quality\QualityPlaceHandler;
use common\models\parts\car\CarType;
use yii\base\InvalidCallException;
use yii\base\Object;
use Yii;

class QualityPackage extends Object
{

    public $id;
    public $AR;

    public function init()
    {
        if (!$this->id || !$this->AR = QualityPackageAR::findOne($this->id)) throw new InvalidCallException();
    }

    //获取套餐名称
    public function getName()
    {
        return $this->AR->name;
    }

    //获取下属施工部位
    public function getPlace()
    {

        $data = Yii::$app->RQ->AR(new QualityPackagePlaceAR())->all([
            'where' => ['quality_package_id' => $this->id],
            'select' => ['quality_place_id','quality_material_id'],
        ]);

        $list = [];
        foreach ($data as $key => $var) {
            $place = new QualityPlace(['id' => $var['quality_place_id']]);
            if ($place->getType() == QualityPlace::TYPE_NORMAL) {
                $material=new QualityMaterial(['id'=>$var['quality_material_id']]);
                $list[] = [
                    'id' => $place->id,
                    'name' => $place->getName(),
                    'type' => $place->getType(),
                    'material_name'=>$material->getName(),
                    'material' =>$material->getAttribute(),
                ];
            }
        }
        return $list;
    }


    //获取报价信息
    public function getPriceList(CarType $type)
    {
        $where = "quality_package_id='$this->id' and car_type_id='$type->id'";

        $data = Yii::$app->RQ->AR(new QualityPriceAR())->all([
            'where' => $where,
            'select' => ['price', 'area', 'hard', 'time', 'quality_place_id'],
        ]);
        $list = [];
        foreach ($data as $key => $var) {
            $place = new QualityPlace(['id' => $var['quality_place_id']]);

            if($material_array = Yii::$app->RQ->AR(new QualityPackagePlaceAR())->one([
                'where' => ['quality_package_id' => $this->id,'quality_place_id'=>$place->id],
                'select' => ['quality_material_id'],
            ])){
                $material=new QualityMaterial(['id'=>$material_array['quality_material_id']]);
            }else{
                return false;
            }

            unset($var['quality_place_id']);
            if ($place->getType() == QualityPlace::TYPE_NORMAL) {
                $var['place'] = $place->getName();
                $var["material"] = [
                    'name' => $material->getName(),
                    'attribute' => $material->getAttribute(),
                ];
                $list[] = $var;
            }
        }
        return $list;
    }

    //获取市场价格
    public function getMarketPrice(){
        return $this->AR->market_price;
    }
    //获取全档报价
    public function getPrice()
    {
        return $this->AR->price;
        /*
        $where = "quality_package_id='$this->id' and car_type_id='$type->id'";
        //获取默认全档
        if ($place = QualityPlaceHandler::getDefaultPlace()) {
            $where .= " and quality_place_id='" . $place->id . "'";
        }
        unset($place);


        if ($data = Yii::$app->RQ->AR(new QualityPriceAR())->one([
            'where' => $where,
            'select' => ['price', 'area', 'hard', 'time', 'quality_place_id'],
        ])
        ) {
            //获取部位
            $place = new QualityPlace(['id' => $data['quality_place_id']]);
            //获取配置材料
            if($material_array=Yii::$app->RQ->AR(new QualityPackagePlaceAR())->one([
                'where'=>['quality_package_id'=>$this->id,'quality_place_id'=>$place->id],
                'select'=>['quality_material_id'],
            ])){
                //已配置材料
                $material=new QualityMaterial(['id'=>$material_array['quality_material_id']]);
            }else{
                //未配置材料
                return false;
            }
            $data['place'] = $place->getName();
            $data["material"] = [
                'name' => $material->getName(),
                'attribute' => $material->getAttribute(),
            ];
            return $data;
        }

        return false;
        */

    }


}