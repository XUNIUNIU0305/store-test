<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 16:19
 */

namespace api\models;


use common\ActiveRecord\CarTypeAR;
use common\ActiveRecord\QualityPackageAR;
use common\ActiveRecord\QualityPlaceAR;
use common\components\handler\quality\QualityPackageHandler;
use common\components\handler\quality\QualityPlaceHandler;
use common\components\handler\quality\QualityPriceHandler;
use common\models\Model;
use common\models\parts\car\CarType;
use common\models\parts\quality\QualityPackage;
use common\models\parts\quality\QualityPlace;

class QualityModel extends Model
{

    const SCE_GET_PACKAGE_LIST="get_package_list";// 获取套餐列表
    const SCE_GET_PLACE="get_place";//获取施工位置

    const SCE_GET_BUSINESS_PACKAGE_LIST="get_business_package_list";// 获取Business套餐列表
    const SCE_GET_BUSINESS_PLACE="get_business_place";//获取Business施工位置


    const SCE_GET_ATTRIBUTE="get_attribute";//获取属性及报价
    const SCE_GET_PRICE='get_price';//获取价格信息
    const SCE_GET_PRICE_LIST="get_price_list";//获取报价列表


    public $package_id;
    public $place_id;
    public $type_id;
    public $type;

    public function scenarios()
    {
        return [
            self::SCE_GET_PACKAGE_LIST=>[],
            self::SCE_GET_PLACE=>['type'],

            self::SCE_GET_BUSINESS_PACKAGE_LIST=>[],
            self::SCE_GET_BUSINESS_PLACE=>['type'],


            self::SCE_GET_ATTRIBUTE=>['package_id'],
            self::SCE_GET_PRICE=>['package_id','place_id','type_id'],
            self::SCE_GET_PRICE_LIST=>['type_id','package_id'],
        ];
    }

    public function rules()
    {
        return [
            [
                ['type'],
                'default',
                'value'=>1,
            ],
            [
                ['type'],
                'in',
                'range'=>[QualityPlace::TYPE_NORMAL,QualityPlace::TYPE_ALL],
                'message'=>7089,
            ],

            [
                ['package_id','place_id'],
                'required',
                'message'=>9001,
            ],
            [
                ['package_id'],
                'exist',
                'targetClass'=>QualityPackageAR::className(),
                'targetAttribute'=>['package_id'=>'id'],
                'message'=>7061,
            ],
/*
            [
                ['place_id'],
                'exist',
                'targetClass'=>QualityPlaceAR::className(),
                'targetAttribute'=>['place_id'=>'id'],
                'message'=>7062,
            ],
*/
            [
                ['type_id'],
                'exist',
                'targetClass'=>CarTypeAR::className(),
                'targetAttribute'=>['type_id'=>'id'],
                'message'=>7062,
            ],


        ];
    }

    //获取报价列表
    public function getPriceList(){
        $this->type_id=1;//暂时定为一套数据

        if($list=(new QualityPackage(['id'=>$this->package_id]))->getPriceList(new CarType(['id'=>$this->type_id]))){
            return $list;
        }
        $this->addError('getPriceList',7091);
        return false;
    }
    //获取报价信息
    public function getPrice(){
        $this->type_id=1;//暂时定为一套数据
        $package=new QualityPackage(['id'=>$this->package_id]);
        return [
            'price'=>$package->getPrice(),
            'market_price'=>$package->getMarketPrice(),
        ];
        /*
        if($info= $package->getPrice(new CarType(['id'=>$this->type_id]))){
            return ['price'=>$info];
        }
        $this->addError('getPrice',7090);
        return false;*/
    }

    //获取套餐价格及属性信息
    public function getAttribute(){

         if($info=(new QualityPackage(['id'=>$this->package_id]))->getPlace()){
             return $info;
         }
         $this->addError('getAttribute',7092);
         return false;
    }

    //获取施工位置
    public function getPlace(){
        if($list=QualityPlaceHandler::getList($this->type)){
            return $list;
        }
        $this->addError('getPlace',7093);
        return false;
    }

    //获取套餐列表
    public function getPackageList(){
        if($list=QualityPackageHandler::getList()){
            return $list;
        }
        $this->addError('getPackageList',7094);
        return false;
    }

    //获取施工位置
    public function getBusinessPlace(){
        if($list=QualityPlaceHandler::getBusinessList($this->type)){
            return $list;
        }
        $this->addError('getPlace',7093);
        return false;
    }

    //获取套餐列表
    public function getBusinessPackageList(){
        if($list=QualityPackageHandler::getBusinessList()){
            return $list;
        }
        $this->addError('getPackageList',7094);
        return false;
    }
}