<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/26 0026
 * Time: 17:50
 */

namespace business\modules\membrane\models;


use business\models\parts\Area;
use common\ActiveRecord\BusinessAreaAR;
use common\ActiveRecord\MembraneOrderAR;
use common\components\handler\Handler;
use common\models\Model;
use yii\data\ActiveDataProvider;
use common\models\parts\MembraneOrder;
use business\models\parts\Role;

class HomeModel extends Model
{

    const SCE_SEARCH = 'search';
    const SCE_ACCEPT = 'accept';
    const SCE_FINISH = 'finish';
    const SCE_CANCEL = 'cancel';

    public $receive_name;
    public $receive_address;
    public $created_start;
    public $created_end;
    public $order_number;
    public $buy_account;
    public $pay_start;
    public $pay_end;
    public $status;
    public $page;
    public $page_size;
    public $no;

    public function scenarios()
    {
        return [
            self::SCE_SEARCH => [
                'receive_name',
                'receive_address',
                'created_start',
                'created_end',
                'order_number',
                'buy_account',
                'pay_start',
                'pay_end',
                'status',
                'page',
                'page_size'
            ],
            self::SCE_ACCEPT => [
                'order_number'
            ],
            self::SCE_FINISH => [
                'order_number'
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
                ['receive_name', 'receive_address', 'buy_account', 'order_number', 'no'],
                'string',
                'message' => 9002
            ],
            [
                ['created_start', 'create_end', 'pay_start', 'pay_end'],
                'date',
                'format' => 'Y-m-d',
                'message' => 9002
            ],
            [
                'status',
                'in',
                'range' => array_keys(MembraneOrder::$status)
            ],
            [
                'page',
                'default',
                'value' => 1
            ],
            [
                'page_size',
                'default',
                'value' => 5
            ],
            [
                'order_number',
                'required',
                'on' => [self::SCE_ACCEPT, self::SCE_FINISH]
            ],
            [
                ['no'],
                'required',
                'message' => 9001
            ]
        ];
    }

    public static $statusLabel = [
        MembraneOrder::STATUS_PAYED => '已付款',
        MembraneOrder::STATUS_ACCEPTED => '已接单',
        MembraneOrder::STATUS_FINISHED => '已完成',
        MembraneOrder::STATUS_CANCELED => '已取消'
    ];

    public function search()
    {
        try{
            $areaId = \Yii::$app->RQ->AR(new BusinessAreaAR())->column([
                'select' => ['id'],
                'where' => ['business_user_asleader_id' => \Yii::$app->user->id, 'level' => Area::LEVEL_QUATERNARY]
            ]);
            $query = MembraneOrderAR::find()
                ->orderBy(['id' => SORT_DESC])
                ->where(['business_fourth_area_id' => $areaId, 'status' => array_keys(self::$statusLabel)]);
            $query->andFilterWhere(['like', 'receive_name', $this->receive_name]);
            $query->andFilterWhere(['like', 'receive_address', $this->receive_address]);
            $query->andFilterWhere(['like', 'custom_user_account', $this->buy_account]);
            $query->andFilterWhere(['like', 'order_number', $this->order_number]);
            $query->andFilterWhere(['>', 'created_date', $this->created_start]);
            if($this->created_end){
                $query->andFilterWhere(['<=', 'created_date', $this->created_end . ' 23:59:59']);
            }
            $query->andFilterWhere(['>', 'pay_date', $this->pay_start]);
            if($this->pay_end){
                $query->andFilterWhere(['<=', 'pay_date', $this->pay_end . ' 23:59:59']);
            }
            $query->andFilterWhere(['status' => $this->status]);
            $provider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'page' => $this->page - 1,
                    'pageSize' => $this->page_size
                ]
            ]);
            $items = array_map(function($item){
                $obj = new MembraneOrder(['AR' => $item]);
                return Handler::getMultiAttributes($obj, [
                    'no',
                    'account',
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
                                                    'type' => 'membrane_item_type'
                                                ]);
                                            }, $attributes);
                                        }
                                    ]
                                ]);
                            }, $items);
                        }
                    ]
                ]);
            }, $provider->getModels());
            return [
                'items' => $items,
                'page'  => $this->page,
                'page_size' => $this->page_size,
                'count' => $provider->totalCount
            ];
        } catch (\Exception $e){
            $this->addError('search', 1);
            return false;
        }
    }

    public function accept()
    {
        try{
            $model = new MembraneOrder(['no' => $this->order_number]);
            $uid = \Yii::$app->user->id;
            $model->toAccept($uid);
            return [];
        } catch (\Exception $e){
            $this->addError('accept', 13361);
            return false;
        }
    }

    public function finish()
    {
        try{
            $model = new MembraneOrder(['no' => $this->order_number]);
            $model->toFinish();
            return true;
        } catch (\Exception $e){
            $this->addError('finish', 13362);
            return false;
        }
    }

    /**
     * @return bool
     */
    public function cancel()
    {
        try {
            $model = new MembraneOrder(['no' => $this->no]);
            $model->businessCancel();
            return true;
        } catch (\Exception $e){
            $this->addError('cancel', 13360);
            return false;
        }
    }
}
