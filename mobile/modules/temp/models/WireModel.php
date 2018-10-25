<?php
namespace mobile\modules\temp\models;

use Yii;
use common\models\Model;
use common\models\parts\temp\SimaQuery;

class WireModel extends Model{

    const SCE_GET_CAR_BRAND_LIST = 'get_car_brand_list';
    const SCE_GET_WIRE_LIST = 'get_wire_list';
    const SCE_GET_CAR_TYPE_LIST = 'get_car_type_list';
    const SCE_GET_WIRE_DETAIL = 'get_wire_detail';
    const SCE_GET_WIRE_IMAGE = 'get_wire_image';

    public $car_brand_id;
    public $car_type_id;
    public $wire_id;

    public function scenarios(){
        return [
            self::SCE_GET_CAR_BRAND_LIST => [],
            self::SCE_GET_WIRE_LIST => [],
            self::SCE_GET_CAR_TYPE_LIST => [
                'car_brand_id',
            ],
            self::SCE_GET_WIRE_DETAIL => [
                'wire_id',
            ],
            self::SCE_GET_WIRE_IMAGE => [
                'wire_id',
                'car_type_id',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['car_brand_id'],
                'required',
                'message' => 9001,
            ],
            [
                ['wire_id'],
                'required',
                'message' => 9001,
                'on' => self::SCE_GET_WIRE_DETAIL,
            ],
            [
                ['car_brand_id', 'car_type_id'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['wire_id'],
                'string',
                'length' => [1, 4],
                'tooShort' => 9002,
                'tooLong' => 9002,
                'message' => 9002,
            ],
        ];
    }

    public function getWireImage(){
        $sima = new SimaQuery;
        if($this->wire_id){
            $wireId = $this->wire_id;
            $imageUrl = $sima->getWireImage($this->wire_id);
        }elseif($this->car_type_id){
            if($list = $sima->getCarWire($this->car_type_id)){
                $wireId = $list['number'] ? ("{$list['wireimg']}-{$list['number']}") : $list['wireimg'];
                $imageUrl = $sima->getWireImage($wireId);
            }else{
                $this->addError('getCarWire', 10091);
                return false;
            }
        }else{
            $this->addError('getWireImage', 9001);
            return false;
        }
        return [
            'url' => $imageUrl,
            'wire_id' => $wireId,
        ];
    }

    public function getWireDetail(){
        $sima = new SimaQuery;
        if($list = $sima->getWireDetail($this->wire_id)){
            return $list;
        }else{
            $this->addError('getWireDetail', 10091);
            return false;
        }
    }

    public function getCarWire(){
        $sima = new SimaQuery;
        if($list = $sima->getCarWire($this->car_type_id)){
            $wireId = $list['number'] ? ("{$list['wireimg']}-{$list['number']}") : $list['wireimg'];
            return [
                'url' => $sima->getWireImage($wireId),
            ];
        }else{
            $this->addError('getCarWire', 10091);
            return false;
        }
    }

    public function getCarTypeList(){
        $sima = new SimaQuery;
        if($list = $sima->getCarTypeList($this->car_brand_id)){
            return $list;
        }else{
            $this->addError('getCarTypeList', 10091);
            return false;
        }
    }

    public function getCarBrandList(){
        $sima = new SimaQuery;
        if($list = $sima->carBrandList){
            return $list;
        }else{
            $this->addError('getCarBrandList', 10091);
            return false;
        }
    }

    public function getWireList(){
        $sima = new SimaQuery;
        if($list = $sima->wireList){
            return $list;
        }else{
            $this->addError('getWireList', 10091);
            return false;
        }
    }
}
