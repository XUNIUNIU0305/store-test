<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/5/31
 * Time: 下午8:51
 */

namespace custom\modules\quality\models;


use common\ActiveRecord\QualityCarAR;
use common\ActiveRecord\QualityCarOptionAR;
use common\models\Model;
use Yii;

class PriceModel extends Model
{

    const SCE_CAR_BRAND = 'get_car_brand';
    const SCE_CAR_TYPE = 'get_car_type';
    const SCE_CAR_FACTOR = 'get_car_factor';

    public $brand_id;
    public $type_id;
    private $package_price = [
        '1'=>6720,
        '2'=>5520,
        '3'=>3520,
        '4'=>2392,
        '5'=>1992,
    ];

    public $package;

    public function scenarios()
    {
        return [
            self::SCE_CAR_BRAND => [],
            self::SCE_CAR_TYPE => ['brand_id'],
            self::SCE_CAR_FACTOR => ['brand_id','type_id','package'],
        ];
    }

    public function rules()
    {
        return [
            [
                ['brand_id','type_id'],
                'required',
                'message'=>9001,
            ],
            [
                ['brand_id','type_id','package'],
                'integer',
                'message'=>9002,
            ],
            [
                ['brand_id'],
                'exist',
                'targetClass'=>QualityCarAR::className(),
                'targetAttribute'=>['brand_id'=>'id'],
                'message'=>9002,
            ],
            [
                ['type_id'],
                'exist',
                'targetClass'=>QualityCarAR::className(),
                'targetAttribute'=>['type_id'=>'id'],
                'message'=>9002,
            ],
            [
                ['package'],
                'in',
                'range'=>[1,2,3,4,5],
                'message'=>9002
            ]
        ];
    }


    public function getCarBrand(){
        return Yii::$app->RQ->AR(new QualityCarAR())->all([
            'select'=>['id','name'],
            'where'=>[
                'parent'=>0,
            ],
        ]);
    }

    public function getCarType(){
        return Yii::$app->RQ->AR(new QualityCarAR())->all([
            'select'=>['id','name'],
            'where'=>[
                'parent'=>$this->brand_id,
            ],
        ]);
    }

    public function getCarFactor(){
        $factor = Yii::$app->RQ->AR(new QualityCarOptionAR())->scalar([
            'select'=>['factor'],
            'where'=>[
                'brand_id'=>$this->brand_id,
                'type_id'=>$this->type_id,
            ],
        ]);
        //16800  13800  8800  5980
        $price = bcmul($this->package_price[$this->package],$factor,2);
        switch ($this->package){
        case 1:
            $price = $price <= 16800 ? 16800 : bcmul(ceil(bcmul($price,0.01)),100);
            break;
        case 2:
            $price = $price <= 13800 ? 13800 : bcmul(ceil(bcmul($price,0.01)),100);
            break;
        case 3:
            $price = $price <= 8800 ? 8800 : bcmul(ceil(bcmul($price,0.01)),100);
            break;
        case 4:
            $price = $price <= 5980 ? 5980 : bcmul(ceil(bcmul($price,0.01)),100);
            break;
        case 5:
            $price = $price <= 4980 ? 4980 : bcmul(ceil(bcmul($price,0.01)),100);
            break;
        }
        return ['price'=>$price];
    }
}
