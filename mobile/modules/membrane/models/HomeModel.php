<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/28 0028
 * Time: 11:51
 */

namespace mobile\modules\membrane\models;

use common\components\handler\Handler;
use common\components\handler\MembraneOrderHandler;
use common\components\handler\MembraneOrderItemHandler;
use common\components\handler\MembraneProductHandler;
use custom\models\parts\UrlParamCrypt;
use custom\components\handler\CustomRechargeApplyHandler;
use custom\components\handler\TradeHandler;
use custom\models\parts\trade\PaymentMethod;
use yii\helpers\Url;
use common\models\parts\Address;
use yii\web\NotFoundHttpException;
use Yii;

class HomeModel extends \custom\modules\membrane\models\HomeModel
{
    const SCE_MOBILE_ORDER = 'mobile_order';
    const SCE_PRODUCT = 'get_product';

    public $way;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCE_MOBILE_ORDER] = [
            'way',
            'address',
            'items'
        ];
        $scenarios[self::SCE_PRODUCT] = [];
        return $scenarios;
    }

    public function rules()
    {
        return [
            [
                'way',
                'in',
                'range' => [ PaymentMethod::METHOD_BALANCE, PaymentMethod::METHOD_WX_INWECHAT ],
                'message' => 3104
            ],
            [
                'way',
                'required',
                'message' => 9002
            ]
        ];
    }

    public function mobileOrder()
    {
        if(!Yii::$app->CustomUser->CurrentUser->area->parent->leader){
            $this->addError('mobileOrder', 3391);
            return false;
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $address = new Address(['id' => $this->address]);
            $user = \Yii::$app->CustomUser->CurrentUser;
            $items = MembraneOrderItemHandler::parseItems($this->items);
            $orders = MembraneOrderHandler::batchInsert($items, $address, $user);
            $trade = TradeHandler::createMembraneTrade($orders, $this->way, $user->id);

            if(!PaymentMethod::canPay($this->way, $trade->totalFee)){
                $transaction->rollBack();
                $this->addError('generateTrade', 3331);
                return false;
            }
            if($trade->needRecharge){
                $paymentMethod = new PaymentMethod(['method' => $this->way]);
                if(!$rechargeApply = CustomRechargeApplyHandler::create($trade->totalFee, $paymentMethod, $trade))throw new \Exception;
                $rechargeUrl = $rechargeApply->generateRechargeUrl();
                $callBack = ['url' => $rechargeUrl];
            }else{
                if(\Yii::$app->CustomUser->wallet->pay($trade)){
                    $q = (new UrlParamCrypt)->encrypt($trade->totalFee);
                    $callBack = ['url' => Url::to(['/trade/balance', 'q' => $q])];
                }else{
                    throw new \Exception;
                }
            }
            $transaction->commit();
            return $callBack;
        } catch (\Exception $e){
            $transaction->rollBack();
            $this->addError('', 3331);
            return false;
        }
    }

    public function getProduct()
    {
        try {
            $res = MembraneProductHandler::findAll();
            return array_map(function($item){
                return Handler::getMultiAttributes($item, [
                    'image',
                    'blocks' => 'blocksLabel',
                    'params' => 'productParams',
                    'remark',
                    '_func' => [
                        'productParams' => function($params) {
                            return array_map(function($param){
                                return [
                                    'id' => $param->id,
                                    'name' => $param->name,
                                    'coefficient' => $param->coefficient,
                                    'price' => $param->price,
                                    'orig_price' => $param->origPrice,
                                    'min_price' => $param->minPrice
                                ];
                            }, $params);
                        }
                    ]
                ]);
            }, $res);
        } catch (\Exception $e){
            $this->addError('', 3350);
            return false;
        }
    }
}
