<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/25 0025
 * Time: 14:26
 */

namespace custom\modules\membrane\models;

use common\ActiveRecord\PaymentMethodAR;
use common\components\handler\MembraneOrderHandler;
use common\components\handler\MembraneOrderItemHandler;
use common\models\Model;
use common\models\parts\Address;
use common\models\parts\MembraneOrder;
use custom\components\handler\CustomRechargeApplyHandler;
use custom\components\handler\TradeHandler;
use custom\models\parts\trade\PaymentMethod;
use custom\models\parts\UrlParamCrypt;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use Yii;

class HomeModel extends Model
{
    const SCE_ADDRESS = 'get_address';
    const SCE_PAYMENT = 'get_payment';
    const SCE_ORDER = 'post_order';  //下单
    const SCE_STATUS = 'order_status';
    const SCE_BALANCE = 'get_balance';

    public $method;             //支付方式
    public $address;            //收货地址
    public $items;           //产品

    public function scenarios()
    {
        return [
            self::SCE_ADDRESS => [],
            self::SCE_PAYMENT => [],
            self::SCE_ORDER => [
                'method',
                'address',
                'items'
            ],
            self::SCE_STATUS => [],
            self::SCE_BALANCE => []
        ];
    }

    public function rules()
    {
        return [
            [
                ['method', 'address'],
                'required',
                'message' => 9001
            ],
            [
                'method',
                'in',
                'range' => self::getPaymentMethod(),
                'message' => 3104
            ],
            [
                'address',
                'integer',
                'message' => 3071
            ],
            [
                'items',
                'validateItems'
            ]
        ];
    }

    /**
     * 验证商品结构
     * @param $attribute
     * @param $params
     * @return bool
     */
    public function validateItems($attribute, $params)
    {
        $items = $this->$attribute;
        if(!is_array($items)){
            $this->addError($attribute, 9002);
            return false;
        }

        foreach ($items as $product){
            if(!isset($product['id']) || !isset($product['attributes']) || !is_array($product['attributes'])){
                $this->addError($attribute, 9002);
                return false;
            }
        }
    }

    /**
     * 获取收货地址
     * @return array|bool
     */
    public function getAddress()
    {
        try{
            $addresses = \Yii::$app->CustomUser->address->list;
            usort($addresses, function($a, $b){
                return !$a->isDefault;
            });
            $list = [];
            foreach($addresses as $address){
                $list[] = [
                    'id' => $address->id,
                    'consignee' => $address->consignee,
                    'province' => [
                        'id' => $address->province,
                        'name' => $address->getProvince(true),
                    ],
                    'city' => [
                        'id' => $address->city,
                        'name' => $address->getCity(true),
                    ],
                    'district' => [
                        'id' => $address->district,
                        'name' => $address->getDistrict(true),
                    ],
                    'detail' => $address->detail,
                    'mobile' => $address->mobile,
                    'postal_code' => $address->postalCode,
                    'is_default' => $address->isDefault,
                ];
            }
            return $list;

        }catch (\Exception $exception){
            $this->addError('', 3332);
            return false;
        }
    }

    public static function getPaymentMethod(){
        $paymentMethod = [PaymentMethod::METHOD_BALANCE, PaymentMethod::METHOD_ALIPAY, PaymentMethod::METHOD_GATEWAY_CORP, PaymentMethod::METHOD_ABCHINA_GATEWAY];
        if(\Yii::$app->CustomUser->CurrentUser->level == 4){
            $paymentMethod = array_merge($paymentMethod, [
                PaymentMethod::METHOD_GATEWAY_PERSON,
            ]);
        }
        return $paymentMethod;
    }

    /**
     * 获取支付方式
     * @return array|bool
     */
    public function getPayment()
    {
        try {
            return \Yii::$app->RQ->AR(new PaymentMethodAR)->all([
                'select' => ['id', 'name', 'img_url'],
                'where' => ['id' => self::getPaymentMethod()],
            ]);
        } catch (\Exception $e) {
            $this->addError('', 3330);
            return false;
        }
    }

    /**
     * @return array|bool
     * @throws \Exception
     */
    public function postOrder()
    {
        if(!Yii::$app->CustomUser->CurrentUser->area->parent->leader){
            $this->addError('postOrder', 3391);
            return false;
        }

        \Yii::$app->db->queryMaster = true;
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $address = new Address(['id' => $this->address]);
            $user = \Yii::$app->CustomUser->CurrentUser;
            $items = MembraneOrderItemHandler::parseItems($this->items);
            $orders = MembraneOrderHandler::batchInsert($items, $address, $user);
            $trade = TradeHandler::createMembraneTrade($orders, $this->method, $user->id);

            if(!PaymentMethod::canPay($this->method, $trade->totalFee)){
                $transaction->rollBack();
                $this->addError('generateTrade', 3331);
                return false;
            }
            if($trade->needRecharge){
                $paymentMethod = new PaymentMethod(['method' => $this->method]);
                if(!$rechargeApply = CustomRechargeApplyHandler::create($trade->totalFee, $paymentMethod, $trade))throw new \Exception;
                $rechargeUrl = $rechargeApply->generateRechargeUrl();
                $callBack = ['url' => $rechargeUrl];
            }else{
                if(\Yii::$app->CustomUser->wallet->pay($trade)){
                    $q = (new UrlParamCrypt)->encrypt($trade->totalFee);
                    $callBack = ['url' => Url::to(['/trade/balance', 'q' => $q,'id'=>$trade->id])];
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

    /**
     * 订单状态
     * @return array
     */
    public function orderStatus()
    {
        return MembraneOrder::$status;
    }

    public function getBalance()
    {
        try {
            $wallet = \Yii::$app->CustomUser->wallet;
            return ['rmb' => floatval($wallet->rmb)];
        }catch (\Exception $e){
            $this->addError('', 9002);
            return false;
        }
    }
}
