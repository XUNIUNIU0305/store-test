<?php
namespace common\models\parts\temp;

use Yii;
use yii\base\InvalidCallException;
use yii\base\Object;
use common\ActiveRecord\WireCarBrandAR;
use common\ActiveRecord\WireCarTypeAR;
use common\ActiveRecord\WireCarWireAR;


class SimaQuery extends Object{

    const URI_HOST = 'http://www.chinasima.com/';
    const URI_INDEX = 'wap/index.php/Test/index';
    const URI_CAR_TYPE = 'wap/index.php/Test/wire_handle';
    const URI_FIND_WIRE = 'wap/index.php/Test/findwire';
    const URI_WIRE_DETAIL = 'wap/index.php/Test/wireimg_handle/wireimg/';

    const CACHE_TYPE_CAR_BRAND_LIST = 1;
    const CACHE_TYPE_CAR_TYPE_LIST = 2;
    const CACHE_TYPE_CAR_WIRE = 3;
    const CACHE_TYPE_WIRE_LIST = 4;
    const CACHE_TYPE_WIRE_DETAIL = 5;
    const CACHE_TYPE_INDEX_HTML = 6;

    const CACHE_PREFIX = '__sima__';
    const CACHE_EXPIRE_TIME = 86400;

    private static $_wireImage;

    public function init(){
        static::$_wireImage = Yii::$app->params['OSS_PostHost'] . '/a/wire/{wireId}.png';
    }

    public function getCarBrandList(){
        return $this->achieveDataAndSave(self::CACHE_TYPE_CAR_BRAND_LIST);
    }

    public function getCarTypeList(int $carBrandId){
        return $this->achieveDataAndSave(self::CACHE_TYPE_CAR_TYPE_LIST, $carBrandId);
    }

    public function getCarWire(int $carTypeId){
        return $this->achieveDataAndSave(self::CACHE_TYPE_CAR_WIRE, $carTypeId);
    }

    public function getWireList(){
        return $this->achieveDataAndSave(self::CACHE_TYPE_WIRE_LIST);
    }

    public function getWireDetail(string $wireId){
        return $this->achieveDataAndSave(self::CACHE_TYPE_WIRE_DETAIL, $wireId);
    }

    public function getWireImage(string $wireId){
        return str_replace('{wireId}', $wireId, static::$_wireImage);
    }

    protected function achieveDataAndSave($type, $args = null){
        switch($type){
            case self::CACHE_TYPE_CAR_BRAND_LIST:
                return $list = array_map(function($item){
                    return [ 'value'   => $item->id,
                        'context' => $item->name
                    ];},WireCarBrandAR::find()->all());
                break;

            case self::CACHE_TYPE_WIRE_LIST:
                return $list = array_map(function($item){
                    return [ 'value'   => $item->number == 0 ? $item->id : $item->id."-".$item->number,
                        'context' => $item->name
                    ];
                },WireCarWireAR::find()->all());
                break;

            case self::CACHE_TYPE_INDEX_HTML:

            case self::CACHE_TYPE_CAR_TYPE_LIST:
                try{
                    $data['status'] = 1;
                    $data['wire'] = array_map(function($item){
                        $car = WireCarBrandAR::findOne(['id' => $item->brand_id]);
                         return $data['wire'] = [
                            'id' => $item->id,
                            'car' => $car->name,
                            'model' => $item->style == '' ? $item->name : $item->name.'--'.$item->style,
                            'wireImg' => $item->wire_id,
                            'number' => 0,
                            'remarks' => $item->remarks
                        ];
                    },WireCarTypeAR::findAll(['brand_id' => $args]));
                    return $data;

                }catch (InvalidCallException $e) {
                    return false;
                }
                break;

            case self::CACHE_TYPE_CAR_WIRE:
                try {
                    $carType = WireCarTypeAR::findOne(['id' => $args]);
                    $car = WireCarBrandAR::findOne(['id' => $carType->brand_id]);
                    $wire = WireCarWireAR::findOne(['id'=>$carType->wire_id]);
                    return [ 'id'  => $carType->id,
                    'car' => $car->name,
                    'model'=> $carType->name,
                    'wireimg'=> $carType->wire_id,
                    'number' => $wire->number,
                    'remarks'=> $carType->remarks,
                ];

                }catch (InvalidCallException $e) {
                    return false;
                }
                break;

            case self::CACHE_TYPE_WIRE_DETAIL:
                if (!is_numeric($args)){
                    $args=strstr($args,'-',TRUE);
                }
                $type = WireCarTypeAR::findAll(['wire_id' => $args]);
                $brand = WireCarTypeAR::find()->select(['brand_id'])->distinct()->where(['wire_id' => $args])->all();
                foreach ($brand as $v){
                    $style = [];
                    foreach ($type as $s) {
                        if ($v->brand_id == $s->brand_id){
                            $style[] = $s->style == '' ? $s->name : $s->name."--".$s->style;
                        }
                    }
                    $data[] = ['brand' => WireCarBrandAR::findOne(['id'=>$v->brand_id])->name,
                            'style' => $style];
                }
                return $data;
                break;
            default:
                return false;
        }
    }
}

