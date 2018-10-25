<?php
namespace custom\modules\account\models;

use Yii;
use common\models\Model;
use custom\models\parts\Menu;
use common\components\handler\Handler;
use common\models\parts\Express;
use common\models\parts\Order;
use custom\models\parts\trade\RechargeMethod;
use common\models\parts\trade\PaymentMethodList;
use custom\components\handler\CustomRechargeApplyHandler;

class IndexModel extends Model{

    const SCE_RECHARGE = 'recharge';
    const SCE_GET_EXPRESS = 'get_express';

    public $rmb;
    public $recharge_method;
    public $order_no;

    public function scenarios(){
        return [
            self::SCE_RECHARGE => [
                'rmb',
                'recharge_method',
            ],
            self::SCE_GET_EXPRESS => [
                'order_no',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['rmb', 'recharge_method', 'order_no'],
                'required',
                'message' => 9001,
            ],
            [
                ['rmb'],
                'number',
                'min' => 0.01,
                'max' => 100000000,
                'tooSmall' => 3191,
                'tooBig' => 3191,
                'message' => 3191,
            ],
            [
                ['recharge_method'],
                'common\validators\order\RechargeValidator',
                'rechargeMethod' => new \custom\models\parts\trade\RechargeMethod,
                'message' => 3192,
            ],
            [
                ['order_no'],
                'common\validators\order\NoValidator',
                'customerId' => Yii::$app->user->id,
                'message' => 3211,
            ],
        ];
    }

    public function getExpress(){
        $express = new Express([
            'order' => new Order(['orderNumber' => $this->order_no]),
        ]);
        if(!$detail = $express->detail){
            $this->addError('getExpress', 3212);
            return false;
        }
        return $detail;
    }

    public static function getUserBalance(){
        return [
            'rmb' => Yii::$app->CustomUser->wallet->rmb,
        ];
    }

    public function recharge(){
        if(!$rechargeApply = CustomRechargeApplyHandler::create($this->rmb, new RechargeMethod(['method' => $this->recharge_method]))){
            $this->addError('recharge', 3193);
            return false;
        }
        $rechargeUrl = $rechargeApply->generateRechargeUrl();
        return ['url' => $rechargeUrl];
    }

    public static function getMenu(){
        return array_map(function($menu){
            return Handler::getMultiAttributes($menu, [
                'id',
                'title',
                'children',
                '_func' => [
                    'children' => function($attribute){
                        if(empty($attribute))return [];
                        return array_map(function($sMenu){
                            return Handler::getMultiAttributes($sMenu, [
                                'id',
                                'parent_id' => 'custom_account_top_menu_id',
                                'title',
                                'url',
                            ]);
                        }, $attribute);
                    }
                ],
            ]);
        }, (new Menu)->getFullMenu());
    }

    public static function getRechargeMethod(){
        $rechargeMethod = new PaymentMethodList(['method' => RechargeMethod::METHOD_ALIPAY]);
        return array_values($rechargeMethod->paymentMethod);
    }
}
