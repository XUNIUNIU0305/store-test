<?php
namespace console\controllers\business;

use Yii;
use common\models\parts\Order;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\OrderBusinessRecordAR;
use common\ActiveRecord\BusinessUserAR;
use common\ActiveRecord\BusinessAreaAR;
use common\ActiveRecord\CustomUserAR;
use business\models\parts\record\BusinessUserRecord;
use business\models\parts\record\BusinessAreaRecord;
use business\models\parts\record\CustomUserRecord;

trait DayAchievement{

    public function actionRecordDay(){
        $today = date('Y-m-d');
        $yesterdayDateTime = new \DateTime($today);
        $yesterdayDateTime->modify('yesterday noon');
        $yesterday = $yesterdayDateTime->format('Y-m-d');
        $orderIds = OrderAR::find()->
            select(['id'])->
            where(['status' => Order::STATUS_CLOSED])->
            andWhere(['>=', 'close_unixtime', strtotime($yesterday . ' 00:00:00')])->
            andWhere(['<=', 'close_unixtime', strtotime($yesterday . ' 23:59:59')])->
            column();
        $BusinessUser = [];
        $BusinessArea = [];
        $CustomUser = [];
        $BusinessUserField = [
            'top_role_id',
            'secondary_role_id',
            'tertiary_role_id',
            'quaternary_role_id',
            'fifth_leader_role_id',
            'fifth_commissar_role_id',
        ];
        $BusinessAreaField = [
            'top_area_id',
            'secondary_area_id',
            'tertiary_area_id',
            'quaternary_area_id',
            'fifth_area_id',
        ];
        foreach($orderIds as $orderId){
            if($record = OrderBusinessRecordAR::findOne(['order_id' => $orderId])){
                $order = new Order(['id' => $orderId]);
                $refund = $order->getRefundPrice();
                $reject = $order->getRejectPrice();
                $normal = $order->totalFee - $refund - $reject;
                foreach($BusinessUserField as $roleName){
                    if($BusinessUserId = $record->{$roleName}){
                        if(isset($BusinessUser[$BusinessUserId])){
                            $BusinessUser[$BusinessUserId]['normal'] += $normal;
                            $BusinessUser[$BusinessUserId]['refund'] += $refund;
                            $BusinessUser[$BusinessUserId]['reject'] += $reject;
                            $BusinessUser[$BusinessUserId]['quantity'] += 1;
                        }else{
                            $BusinessUser[$BusinessUserId] = [
                                'normal' => $normal,
                                'refund' => $refund,
                                'reject' => $reject,
                                'quantity' => 1,
                            ];
                        }
                    }
                }
                foreach($BusinessAreaField as $areaName){
                    $BusinessAreaId = $record->{$areaName};
                    if(isset($BusinessArea[$BusinessAreaId])){
                        $BusinessArea[$BusinessAreaId]['normal'] += $normal;
                        $BusinessArea[$BusinessAreaId]['refund'] += $refund;
                        $BusinessArea[$BusinessAreaId]['reject'] += $reject;
                    }else{
                        $BusinessArea[$BusinessAreaId] = [
                            'normal' => $normal,
                            'refund' => $refund,
                            'reject' => $reject,
                        ];
                    }
                }
                $CustomUserId = $order->getCustomerId();
                if(isset($CustomUser[$CustomUserId])){
                    $CustomUser[$CustomUserId]['normal'] += $normal;
                    $CustomUser[$CustomUserId]['refund'] += $refund;
                    $CustomUser[$CustomUserId]['reject'] += $reject;
                }else{
                    $CustomUser[$CustomUserId] = [
                        'normal' => $normal,
                        'refund' => $refund,
                        'reject' => $reject,
                    ];
                }
            }
        }
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach($BusinessUser as $userId => $data){
                $record = new BusinessUserRecord(['id' => $userId]);
                $record->recordDay([
                    'normal_rmb' => $data['normal'],
                    'refund_rmb' => $data['refund'],
                    'reject_rmb' => $data['reject'],
                    'date' => $yesterday,
                ]);
                $record->recordOrderQuantity($data['quantity']);
            }
            foreach($BusinessArea as $areaId => $data){
                $record = new BusinessAreaRecord(['id' => $areaId]);
                $record->recordDay([
                    'normal_rmb' => $data['normal'],
                    'refund_rmb' => $data['refund'],
                    'reject_rmb' => $data['reject'],
                    'date' => $yesterday,
                ]);
            }
            foreach($CustomUser as $userId => $data){
                $record = new CustomUserRecord(['id' => $userId]);
                $record->recordDay([
                    'normal_rmb' => $data['normal'],
                    'refund_rmb' => $data['refund'],
                    'reject_rmb' => $data['reject'],
                    'date' => $yesterday,
                ]);
            }
            $BusinessUserHaveRecord = array_keys($BusinessUser);
            $BusinessAreaHaveRecord = array_keys($BusinessArea);
            $CustomUserHaveRecord = array_keys($CustomUser);
            $this->emptyBusinessUserRecord($BusinessUserHaveRecord, $yesterday);
            $this->emptyBusinessAreaRecord($BusinessAreaHaveRecord, $yesterday);
            $this->emptyCustomUserRecord($CustomUserHaveRecord, $yesterday);
            $transaction->commit();
            return 0;
        }catch(\Exception $e){
            $transaction->rollBack();
            return 1;
        }
    }

    protected function emptyBusinessUserRecord(array $haveRecordUser, $day){
        $userIds = Yii::$app->RQ->AR(new BusinessUserAR)->column([
            'select' => ['id'],
            'where' => ['business_role_id' => [3, 4, 5, 6, 7, 8]],
        ]);
        foreach($userIds as $userId){
            if(in_array($userId, $haveRecordUser))continue;
            $record = new BusinessUserRecord(['id' => $userId]);
            $record->recordDay([
                'normal_rmb' => 0,
                'refund_rmb' => 0,
                'reject_rmb' => 0,
                'date' => $day, 
            ]);
        }
    }

    protected function emptyBusinessAreaRecord(array $haveRecordArea, $day){
        $areaIds = Yii::$app->RQ->AR(new BusinessAreaAR)->column([
            'select' => ['id'],
            'where' => [
                'display' => 1,
            ],
        ]);
        foreach($areaIds as $areaId){
            if(in_array($areaId, $haveRecordArea))continue;
            $record = new BusinessAreaRecord(['id' => $areaId]);
            $record->recordDay([
                'normal_rmb' => 0,
                'refund_rmb' => 0,
                'reject_rmb' => 0,
                'date' => $day,
            ]);
        }
    }

    protected function emptyCustomUserRecord(array $haveRecordUser, $day){
        $userIds = Yii::$app->RQ->AR(new CustomUserAR)->column([
            'select' => ['id'],
        ]);
        foreach($userIds as $userId){
            if(in_array($userId, $haveRecordUser))continue;
            $record = new CustomUserRecord(['id' => $userId]);
            $record->recordDay([
                'normal_rmb' => 0,
                'refund_rmb' => 0,
                'reject_rmb' => 0,
                'date' => $day,
            ]);
        }
    }
}
