<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/25 0025
 * Time: 16:58
 */

namespace common\models\parts;

use common\ActiveRecord\AdminPayMembraneOrderAR;
use common\ActiveRecord\CustomUserTradeAR;
use common\ActiveRecord\CustomUserTradeMembraneAR;
use common\ActiveRecord\MembraneOrderItemAR;
use common\models\parts\trade\WalletAbstract;
use custom\models\parts\trade\Wallet;
use yii\base\InvalidConfigException;
use yii\base\Object;
use common\ActiveRecord\MembraneOrderAR;
use yii\db\Expression;
use yii\web\BadRequestHttpException;

/**
 * Class MembraneOrder
 * @package common\models\parts
 * @property float $price
 * @property $id int
 * @property $AR MembraneOrderAR
 */
class MembraneOrder extends Object
{
    const STATUS_DEFAULT = 1;      //已下单
    const STATUS_PAYED = 2;        //已付款
    const STATUS_ACCEPTED = 3;     //已接单
    const STATUS_FINISHED = 4;
    const STATUS_CANCELED = 5;

    public static $status = [
        self::STATUS_DEFAULT => '未付款',
        self::STATUS_PAYED => '已付款',
        self::STATUS_ACCEPTED => '已接单',
//        self::STATUS_SEND => '已发货',
        self::STATUS_FINISHED => '已完成',
        self::STATUS_CANCELED => '已取消'
    ];

    public static $activeStatus = [
        self::STATUS_PAYED,
        self::STATUS_ACCEPTED,
        self::STATUS_FINISHED,
        self::STATUS_CANCELED
    ];

    /**
     * 有效订单
     * @var array
     */
    public static $validStatus = [
        self::STATUS_PAYED,
        self::STATUS_ACCEPTED,
        self::STATUS_FINISHED
    ];

    /**
     * @var MembraneOrderAR $AR
     */
    private $AR;

    public $id;

    public $no;

    public function init()
    {
        if(!$this->AR){
            if($this->id)
                $this->AR = MembraneOrderAR::findOne($this->id);
            elseif($this->no)
                $this->AR = MembraneOrderAR::findOne(['order_number' => $this->no]);
        }
        if(!$this->AR instanceof MembraneOrderAR)
            throw new InvalidConfigException();
        $this->id = $this->AR->id;
        $this->no = $this->AR->order_number;
    }

    public function setAR($ar)
    {
        $this->AR = $ar;
    }

//    private $price;
//    /**
//     * 获取订单价格
//     */
//    public function getPrice()
//    {
//        if($this->price === null){
//            $items = $this->getItems();
//            foreach ($items as $item){
//                $this->price += $item->price;
//            }
//        }
//        return $this->price;
//    }

    private $items = false;
    /**
     * 获取订单产品
     * @return array
     */
    public function getItems()
    {
        if($this->items === false){
            $this->items = [];
            $items = MembraneOrderItemAR::find()
                ->where(['membrane_order_id'=>$this->id])
                ->all();
            $this->items = array_map(function($item){
                return new MembraneOrderItem(['AR' => $item]);
            },$items);
        }
        return $this->items;
    }

    private $payMethod;

    /**
     * @return integer
     * 获取支付方式
     */
    public function getPayMethod()
    {
        if(!$this->payMethod){
            $id = CustomUserTradeMembraneAR::find()
                ->select(['custom_user_trade_id'])
                ->where(['membrane_order_id' => $this->AR->id])
                ->column();

            $res = CustomUserTradeAR::find()
                ->select(['payment_method'])
                ->where(['id' => $id])
                ->orderBy(['status' => SORT_DESC])
                ->limit(1)
                ->column();
            $this->payMethod = current($res);
        }
        return $this->payMethod;
    }

    public function getReceiveName()
    {
        return $this->AR->receive_name;
    }

    public function getReceiveAddress()
    {
        return $this->AR->receive_address;
    }

    public function getReceiveMobile()
    {
        return $this->AR->receive_mobile;
    }

    public function getReceiveCode()
    {
        return $this->AR->receive_code;
    }

    public function getRemark()
    {
        return $this->AR->remark;
    }

    public function getCreatedDate()
    {
        return $this->AR->created_date;
    }

    public function getPayDate()
    {
        return $this->AR->pay_date ?? '';
    }

    public function getAcceptDate()
    {
        return $this->AR->accept_date;
    }

    public function getFinishDate()
    {
        return $this->AR->finish_date;
    }

    public function getStatus()
    {
        return $this->AR->status;
    }

    public function getAccount()
    {
        return $this->AR->custom_user_account;
    }

    public function getTotalFee()
    {
        return floatval($this->AR->total_fee);
    }

    /**
     * 更新为已支付
     * @throws BadRequestHttpException
     */
    public function toPayed()
    {
        if($this->AR->status !== self::STATUS_DEFAULT)
            throw new BadRequestHttpException;
        $this->AR->pay_date = new Expression('now()');
        $this->AR->status = self::STATUS_PAYED;
        $this->AR->update();
    }

    /**
     * 接单
     * @param $id
     * @throws BadRequestHttpException
     */
    public function toAccept($id)
    {
        if($this->AR->status !== self::STATUS_PAYED)
            throw new BadRequestHttpException;
        $this->AR->accept_date = new Expression('now()');
        $this->AR->status = self::STATUS_ACCEPTED;
        $this->AR->business_third_area_leader_id = $id;
        $this->AR->update();
    }

    /**
     * 完成
     * @throws BadRequestHttpException
     */
    public function toFinish()
    {
        if($this->AR->status !== self::STATUS_ACCEPTED)
            throw new BadRequestHttpException;
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $this->AR->finish_date = new Expression('now()');
            $this->AR->status = self::STATUS_FINISHED;
            $this->AR->update();
            $this->payToWallet();
            $transaction->commit();
        } catch (\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * 支付到运营商钱包
     */
    public function payToWallet()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $wallet = new \business\models\parts\trade\Wallet([
                'userId' => $this->AR->business_third_user_id,
                'receiveType' => WalletAbstract::RECEIVE_MEMBRANE_ORDER_FINISH
            ]);

            $adminWallet = new \admin\models\parts\trade\Wallet;
            if(!$adminWallet->pay($this, $wallet)) throw new \Exception('打款失败');
            $transaction->commit();
        }catch(\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * 用户取消
     * @throws BadRequestHttpException
     */
    public function customCancel()
    {
        if($this->AR->status != self::STATUS_PAYED && $this->AR->status != self::STATUS_DEFAULT)
            throw new BadRequestHttpException('当前状态不可取消');
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $originalStatus = $this->AR->status;
            $this->AR->cancel_date = new Expression('now()');
            $this->AR->status = self::STATUS_CANCELED;
            $this->AR->update();
            if($originalStatus == self::STATUS_PAYED){
                $this->backToWallet();
            }
            $transaction->commit();
        } catch (\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * 取消订单
     */
    public function businessCancel()
    {
        if(!in_array($this->AR->status, [self::STATUS_PAYED, self::STATUS_ACCEPTED]))
            throw new BadRequestHttpException('当前状态不可取消');
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $this->AR->cancel_date = new Expression('now()');
            $this->AR->status = self::STATUS_CANCELED;
            $this->AR->update();
            $this->backToWallet();
            $transaction->commit();
        } catch (\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * 退回至余额
     * @throws \Exception
     */
    private function backToWallet()
    {
        $customerWallet = new Wallet([
            'userId' => $this->AR->custom_user_id,
            'receiveType' => WalletAbstract::RECEIVE_MEMBRANE_ORDER_CANCELED,
        ]);
        $adminWallet = new \admin\models\parts\trade\Wallet;
        if(!$adminWallet->pay($this, $customerWallet)) throw new \Exception('退款失败');
    }

    public function isPayed()
    {
        return AdminPayMembraneOrderAR::find()->where(['membrane_order_id' => $this->id])->exists();
    }
}
