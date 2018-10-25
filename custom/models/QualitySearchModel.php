<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/9/8
 * Time: 下午1:51
 */

namespace custom\models;

use common\ActiveRecord\QualityOrderAR;
use common\ActiveRecord\QualityOrderItemAR;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\quality\QualityOrder;
use yii\captcha\CaptchaValidator;
use custom\models\parts\sms\SmsCaptcha;
use Yii;
use yii\helpers\ArrayHelper;

class QualitySearchModel extends Model
{
    const SCE_SEARCH_DETAIL_ONE = 'get_search_detail_one';
    const SCE_SEARCH_DETAIL_TWO = 'get_search_detail_two';
    const SCE_SEARCH_DETAIL_THREE = 'get_search_detail_three';
    const SCE_SEARCH_DETAIL_FOUR = 'get_search_detail_four';

    //单号，管芯号，手机号，车架号,车主姓名
    public $order_no,$code,$mobile,$car_frame,$owner_name;

    //搜索类型
    public $type;

    //验证码
    public $captcha;

    //手机验证码
    public $mobile_captcha;

    public function rules()
    {
        return [
            [
                ['order_no','code','mobile','car_frame','owner_name','captcha','mobile_captcha'],
                'required',
                'message'=>9001,
            ],
            [
                ['captcha'],
                'captcha',
                'captchaAction'=>'index/captcha',
                'message'=>3366
            ],
            [
                ['mobile_captcha'],
                'common\validators\SmsValidator',
                'mobile'=>$this->mobile,
                'message'=>3364,
            ],
            [
                ['car_frame'],
                'match',
                'pattern'=>'/^[0-9]{5}$/',
                'message'=>3367
            ],
        ];
    }
    public function scenarios()
    {
        return [
            self::SCE_SEARCH_DETAIL_ONE => ['order_no','code','captcha','car_frame','mobile'],
            self::SCE_SEARCH_DETAIL_TWO => ['order_no','captcha'],
            self::SCE_SEARCH_DETAIL_THREE => ['order_no','owner_name','car_frame','mobile_captcha','mobile'],
            self::SCE_SEARCH_DETAIL_FOUR => ['code','captcha'],
        ];
    }


    public function getSearchDetailOne(){
        $id = Yii::$app->RQ->AR(new QualityOrderAR())->scalar([
            'select'=>['id'],
            'where'=>[
                'code'=>$this->order_no,
                'owner_mobile'=>$this->mobile,
            ],
            'andWhere'=>['like', 'car_frame', '%'.$this->car_frame, false]
        ]);

        if($id && Yii::$app->RQ->AR(new QualityOrderItemAR())->exists(['where'=>['quality_order_id'=>$id,'code' => $this->code]])){
            return Handler::getMultiAttributes(new QualityOrder(['id' => $id]), [
                'id',
                'code',
                'owner_name' => 'ownerName',
                'owner_mobile' => 'ownerMobile',
                'owner_telephone' => 'ownerTelephone',
                'owner_email' => 'email',
                'owner_address' => 'OwnerAddress',
                'car_number' => 'carNumber',
                'car_frame' => 'carFrame',
                'construct_date' => 'constructTime',
                'finished_date' => 'finishedTime',
                'brand_name' => 'Brand',
                'type_name' => 'CarType',
                'construct_unit' => 'constructUnit',
                'goods' => 'Items',
            ]);
        }
        $this->addError('all',3361);
        return false;
    }

    public function getSearchDetailTwo(){

        if ($order = Yii::$app->RQ->AR(new QualityOrderAR())->one([
            'select'=>['code','owner_name'],
            'where'=>[
                'code' => $this->order_no,
            ]
        ])){
            return $order;
        }

        $this->addError('all',3361);
        return false;
    }


    public function getSearchDetailThree(){
        $id = Yii::$app->RQ->AR(new QualityOrderAR())->scalar([
            'select'=>['id'],
            'where'=>[
                'code'=>$this->order_no,
                'owner_name'=>$this->owner_name,
                'owner_mobile'=>$this->mobile,
            ],
            'andWhere'=>['like', 'car_frame', '%'.$this->car_frame, false]
        ]);

        if ($id){
            return Handler::getMultiAttributes(new QualityOrder(['id' => $id]), [
                'id',
                'code',
                'owner_name' => 'ownerName',
                'owner_mobile' => 'ownerMobile',
                'owner_telephone' => 'ownerTelephone',
                'owner_email' => 'email',
                'owner_address' => 'OwnerAddress',
                'car_number' => 'carNumber',
                'car_frame' => 'carFrame',
                'construct_date' => 'constructTime',
                'finished_date' => 'finishedTime',
                'brand_name' => 'Brand',
                'type_name' => 'CarType',
                'construct_unit' => 'constructUnit',
                'goods' => 'Items',
            ]);
        }
        $this->addError('all',3361);
        return false;
    }


    public function getSearchDetailFour(){
        $ids = Yii::$app->RQ->AR(new QualityOrderItemAR())->column([
            'select'=>['quality_order_id'],
            'where'=>[
                'code' => $this->code,
            ]
        ]);

        if ($ids){
            return array_map(function($id){
                return Handler::getMultiAttributes(new QualityOrder(['id'=>$id]),[
                    'id',
                    'code',
                    'construct_unit'=>'constructUnit',
                    'construct_date'=>'constructTime',
                    'finished_date'=>'finishedTime',
                    'brand',
                    'car_type'=>'carType',
                    'items'
                ]);
            },array_values(array_unique($ids)));

        }
        $this->addError('all',3361);
        return false;

    }
}