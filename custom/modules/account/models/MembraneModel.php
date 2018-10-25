<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/28 0028
 * Time: 9:43
 */

namespace custom\modules\account\models;


use common\ActiveRecord\MembraneOrderAR;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\MembraneOrder;
use custom\components\handler\CustomRechargeApplyHandler;
use custom\components\handler\TradeHandler;
use custom\models\parts\trade\PaymentMethod;
use custom\models\parts\UrlParamCrypt;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

class MembraneModel extends Model
{
    const SCE_SEARCH = 'search';
    const SCE_PAY = 'pay';
    const SCE_CANCEL = 'cancel';

    public $no;

    public $created_start;

    public $created_end;

    public $receive_name;

    public $receive_mobile;

    public $receive_address;

    public $status;

    public $page;

    public $page_size;

    public $method;

    public function scenarios()
    {
        return [
            self::SCE_SEARCH => [
                'no',
                'created_start',
                'created_end',
                'receive_name',
                'receive_mobile',
                'receive_address',
                'status',
                'page',
                'page_size'
            ],
            self::SCE_PAY => [
                'method',
                'no'
            ],
            self::SCE_CANCEL => [
                'no'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['no', 'receive_mobile'],
                'integer',
                'message' => 9002
            ],
            [
                ['created_start', 'created_end'],
                'date',
                'format' => 'Y-m-d',
                'message' => 9002
            ],
            [
                ['receive_name', 'receive_address'],
                'string',
                'message' => 9002
            ],
            [
                ['status'],
                'in',
                'range' => array_keys(MembraneOrder::$status),
                'message' => 9002
            ],
            [
                ['page'],
                'default',
                'value' => 1
            ],
            [
                ['page_size'],
                'default',
                'value' => 20
            ],
            [
                ['method', 'no'],
                'required',
                'on' => [self::SCE_PAY, self::SCE_CANCEL],
                'message' => 9001,
            ],
            [
                'method',
                'in',
                'range' => [PaymentMethod::METHOD_BALANCE, PaymentMethod::METHOD_ALIPAY, PaymentMethod::METHOD_ABCHINA_GATEWAY],
                'message' => 9002,
            ]
        ];
    }

    /**
     * @return array
     */
    public function search()
    {
        $query = MembraneOrderAR::find()
            ->orderBy(['id' => SORT_DESC])
            ->where(['custom_user_id' => \Yii::$app->user->id]);
        $query->andFilterWhere(['like', 'order_number', $this->no]);
        $query->andFilterWhere(['like', 'receive_name', $this->receive_name]);
        $query->andFilterWhere(['like', 'receive_address', $this->receive_address]);
        $query->andFilterWhere(['like', 'receive_mobile', $this->receive_mobile]);
        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['>', 'created_date', $this->created_start]);
        if($this->created_end){
            $query->andFilterWhere(['<=', 'created_date', $this->created_end . ' 23:59:59']);
        }

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'page' => $this->page - 1,
                'pageSize' => $this->page_size
            ]
        ]);
        return $this->formatPage($provider);
    }

    /**
     * @param ActiveDataProvider $provider
     * @return array
     */
    protected function formatPage(ActiveDataProvider $provider)
    {
        $items = array_map(function($item){
            $obj = new MembraneOrder(['AR' => $item]);
            return Handler::getMultiAttributes($obj, [
                'no',
                'receiveName',
                'receiveAddress',
                'receiveMobile',
                'receiveCode',
                'remark',
                'createdDate',
                'payDate',
                'acceptDate',
                'finishDate',
                'status',
                'items',
                '_func' => [
                    'items' => function($items){
                        return array_map(function($item){
                            return Handler::getMultiAttributes($item, [
                                'id',
                                'membrane_product_id' => 'membraneProductId',
                                'price',
                                'name',
                                'remark',
                                'image',
                                'attributes',
                                '_func' => [
                                    'attributes' => function($attributes){
                                        return array_map(function($item){
                                            return Handler::getMultiAttributes($item, [
                                                'block' => 'membrane_item_block',
                                                'block_id' => 'membrane_item_block_id',
                                                'type' => 'membrane_item_type',
                                                'type_id' => 'membrane_item_type_id'
                                            ]);
                                        }, $attributes);
                                    }
                                ]
                            ]);
                        }, $items);
                    }
                ]
            ]);
        }, $provider->models);
        return [
            'items' => $items,
            'page' => $this->page,
            'page_size' => $this->page_size,
            'count' => $provider->totalCount
        ];
    }

    public function pay()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $order = new MembraneOrder(['no' => $this->no]);
            $trade = TradeHandler::createMembraneTrade([$order], $this->method, \Yii::$app->user->id);

            if(!PaymentMethod::canPay($this->method, $trade->totalFee)){
                $transaction->rollBack();
                $this->addError('pay', 3331);
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
        } catch (\Exception $exception){
            $transaction->rollBack();
            $this->addError('pay', 3331);
            return false;
        }
    }

    public function cancel()
    {
        try{
            $order = new MembraneOrder(['no' => $this->no]);
            /* start 临时限制 1225订单无法取消 */
            $orderCreateUnixtime = strtotime($order->getCreatedDate());
            if($orderCreateUnixtime >= strtotime('2017-12-25 00:00:00') &&
                $orderCreateUnixtime <= strtotime('2017-12-25 23:59:59')){
                $this->addError('cancelOrders', 3380);
                return false;
            }
            /* end 1225订单无法取消 */
            $order->customCancel();
            return true;
        } catch (\Exception $e){
            $this->addError('cancel', 3351);
            return false;
        }
    }
}
