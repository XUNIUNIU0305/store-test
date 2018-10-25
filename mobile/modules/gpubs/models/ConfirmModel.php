<?php
namespace mobile\modules\gpubs\models;

use Yii;
use common\models\Model;
use custom\models\parts\trade\PaymentMethod;
use common\models\parts\custom\CustomUser;
use common\models\parts\gpubs\GpubsGroupGenerator;
use common\models\parts\gpubs\GpubsGroupTicketGenerator;
use common\models\parts\gpubs\GpubsGroupTicket;
use common\models\parts\gpubs\GpubsGroup;
use common\models\parts\gpubs\GpubsProduct;
use common\models\parts\gpubs\GpubsProductSku;
use common\models\parts\gpubs\GpubsAddress;
use common\models\parts\Address;
use common\ActiveRecord\ActivityGpubsProductSkuAR;
use common\ActiveRecord\ActivityGpubsGroupAR;
use custom\components\handler\TradeHandler;
use custom\components\handler\CustomRechargeApplyHandler;
use custom\models\parts\UrlParamCrypt;
use yii\helpers\Url;

class ConfirmModel extends Model{

    const SCE_ORDER = 'order';

    public $group_id;
    public $product_sku_id;
    public $address_id;
    public $quantity;
    public $comment;
    public $payment_method;

    private $_gpubsGroup;

    public function scenarios(){
        return [
            self::SCE_ORDER => [
                'group_id',
                'product_sku_id',
                'address_id',
                'quantity',
                'comment',
                'payment_method',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['group_id', 'address_id'],
                'default',
                'value' => 0,
            ],
            [
                ['comment'],
                'default',
                'value' => '',
            ],
            [
                ['group_id', 'address_id', 'quantity', 'payment_method'],
                'required',
                'message' => 9001,
            ],
            [
                ['product_sku_id'],
                'exist',
                'targetClass' => 'common\ActiveRecord\ActivityGpubsProductSkuAR',
                'targetAttribute' => ['product_sku_id'],
                'message' => 9002,
            ],
            [
                ['quantity'],
                'integer',
                'min' => 1,
                'tooSmall' => 9002,
                'message' => 9002,
            ],
            [
                ['comment'],
                'string',
                'length' => [0, 255],
                'tooShort' => 9002,
                'tooLong' => 10121,
                'message' => 9002,
            ],
            [
                ['payment_method'],
                'in',
                'range' => [PaymentMethod::METHOD_BALANCE, PaymentMethod::METHOD_WX_INWECHAT],
                'message' => 9002,
            ],
        ];
    }

    public function order(){
        Yii::$app->db->queryMaster = true;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            //区分自提与送货
            $activityProductSku = ActivityGpubsProductSkuAR::findOne([
                'product_sku_id' => $this->product_sku_id,
            ]);
            $product = new GpubsProduct([
                'id' => $activityProductSku->activity_gpubs_product_id,
            ]);
            $sku = new GpubsProductSku([
                'id' => $activityProductSku->id,
            ]);
            $paymentMethod = new PaymentMethod([
                'method' => $this->payment_method,
            ]);
            if($sku->stock < $this->quantity){
                $this->addError('establishGroup', 10128);
                return false;
            }

            if($product->gpubs_type == GpubsProduct::GPUBS_TYPE_INVITE ){

                if($this->address_id && !$this->group_id){
                    $user_address = new GpubsAddress(['id' => $this->address_id]);
                    if(Yii::$app->CustomUser->CurrentUser->level != CustomUser::LEVEL_COMPANY){
                        $this->addError('order', 10123);
                        throw new \Exception;
                    }
                    if($result = $this->establishGroup($product,$sku,$paymentMethod,$user_address)){
                        $transaction->commit();
                        return $result;
                    }else{
                        throw new \Exception;
                    }

                }elseif($this->group_id){
                    $this->_gpubsGroup = new GpubsGroup([
                        'id' => $this->group_id,
                    ]);
                    if(!$this->_gpubsGroup->canJoin()){
                        $this->addError('order', 10124);
                        throw new \Exception;
                    }

                    if($result = $this->joinGroup($sku,$paymentMethod)){
                        $transaction->commit();
                        return $result;
                    }else{
                        throw new \Exception;
                    }
                }else{
                    $this->addError('order', 9001);
                    throw new \Exception;
                }

            }elseif($product->gpubs_type == GpubsProduct::GPUBS_TYPE_DELIVER ){
                $user_address = new Address(['id' => $this->address_id]);

                if ($product->min_quantity_per_member_of_group > $this->quantity){
                    $this->addError('order',10132);
                    throw new \Exception;
                }

                if(!$this->group_id){
                    if($result = $this->establishGroup($product,$sku,$paymentMethod,$user_address)){
                        $transaction->commit();
                        return $result;
                    }else{
                        throw new \Exception;
                    }

                }elseif($this->group_id){
                    $this->_gpubsGroup = new GpubsGroup([
                        'id' => $this->group_id,
                    ]);
                    if(!$this->_gpubsGroup->canJoin()){
                        $this->addError('order', 10124);
                        throw new \Exception;
                    }

                    if($result = $this->joinGroup($sku,$paymentMethod,$user_address)){
                        $transaction->commit();
                        return $result;
                    }else{
                        throw new \Exception;
                    }
                }else{
                    $this->addError('order', 9001);
                    throw new \Exception;
                }

            }else{
                $this->addError('order', 10131);
                throw new \Exception;
            }


        }catch(\Exception $e){
            $transaction->rollBack();
            return false;
        }
    }

    protected function establishGroup($product,$sku,$paymentMethod,$user_address){
        $createdGroupQuantity = Yii::$app->RQ->AR(new ActivityGpubsGroupAR)->count([
            'where' => [
                'activity_gpubs_product_id' => $product->id,
                'custom_user_id' => Yii::$app->user->id,
                'status' => [
                    GpubsGroup::STATUS_WAIT,
                    GpubsGroup::STATUS_UNPAID,
                    GpubsGroup::STATUS_ESTABLISH,
                    GpubsGroup::STATUS_CANCELED,
                    GpubsGroup::STATUS_DELIVERED,
                ],
            ],
        ]);
        if($createdGroupQuantity >= $product->max_launch_per_user){
            $this->addError('establishGroup', 10127);
            return false;
        }
        if($product->status != GpubsProduct::STATUS_ACTIVE){
            $this->addError('establishGroup', 10135);
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try{
            $gpubsGroup = GpubsGroupGenerator::create($product, Yii::$app->CustomUser->CurrentUser,$user_address);
            $gpubsTicket = GpubsGroupTicketGenerator::create($gpubsGroup, $sku, Yii::$app->CustomUser->CurrentUser,$paymentMethod,$user_address, $this->quantity, $this->comment);
            $result = $this->pay($gpubsTicket, $paymentMethod);
            $transaction->commit();
            return $result;
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('establishGroup', 10125);
            return false;
        }
    }

    protected function joinGroup($sku,$paymentMethod,$user_address = 0){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $gpubsTicket = GpubsGroupTicketGenerator::create($this->_gpubsGroup, $sku, Yii::$app->CustomUser->CurrentUser,$paymentMethod,$user_address,$this->quantity, $this->comment);

            $result = $this->pay($gpubsTicket, $paymentMethod);
            $transaction->commit();
            return $result;
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->addError('joinGroup', 10126);
            return false;
        }
    }

    protected function pay(GpubsGroupTicket $ticket, PaymentMethod $paymentMethod){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(!PaymentMethod::canPay($this->payment_method, $ticket->total_fee))throw new \Exception;
            $trade = TradeHandler::createGpubsTrade([$ticket], $paymentMethod);
            if($trade->needRecharge){
                if(!$rechargeApply = CustomRechargeApplyHandler::create($trade->totalFee, $paymentMethod, $trade))throw new \Exception;
                if($rechargeUrl = $rechargeApply->generateRechargeUrl()){
                    $callBack = ['url' => $rechargeUrl .'&group_id='.$ticket->activity_gpubs_group_id.'&p_id='.$ticket->product_id];
                }else{
                    throw new \Exception('creating recharge url failed');
                }
            }else{
                if((new \custom\models\parts\trade\Wallet(['userId' => Yii::$app->user->id]))->pay($trade)){
                    if($paymentMethod->currentPaymentMethod == PaymentMethod::METHOD_BALANCE && (new GpubsGroupTicket(['id' => $ticket->id]))->is_join != GpubsGroupTicket::JOIN_SUCCESS)throw new \Exception;
                    $q = (new UrlParamCrypt)->encrypt($trade->totalFee);
                    $callBack = ['url' => Url::to(['/trade/balance', 'q' => $q,'id'=>$trade->id,'group_id'=>$ticket->activity_gpubs_group_id,'p_id'=>$ticket->product_id])];
                }else{
                    throw new \Exception;
                }
            }
            $transaction->commit();
            return $callBack;
        }catch(\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
    }
}
