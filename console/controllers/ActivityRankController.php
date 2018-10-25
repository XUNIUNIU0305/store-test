<?php
namespace console\controllers;

use Yii;
use common\ActiveRecord\OrderAR;
use common\ActiveRecord\CustomUserAR;
use console\controllers\basic\Controller;
use common\models\parts\Order;
use custom\models\parts\RegisterCode;
use common\models\parts\MembraneOrder;

class ActivityRankController extends Controller{

    public function actionRank(){
        $allRank = $this->generateRank();
        $topRank = count($allRank) > 1000 ? array_slice($allRank, 0, 1000) : array_values($allRank);
        $cacheData = [
            'datetime' => date('Y-m-d H:i:s'),
            'unixtime' => time(),
            'allrank' => $allRank,
            'toprank' => $topRank,
        ];
        Yii::$app->cache->set('activity_rank', $cacheData, 432000);
        return 0;
    }

    protected function generateRank($date = 'now'){
        if($date === 'now'){
            $date = date('Y-m-d');
        }
        $dateFrom = $date . ' 00:00:00';
        $dateTo = $date . ' 23:59:59';
        $orderData = (new \yii\db\Query())
            ->select(['custom_user_id', 'total_fee'])
            ->from('{{%order}}')
            ->where(['>=', 'create_datetime', $dateFrom])
            ->andWhere(['<=', 'create_datetime', $dateTo])
            ->andWhere([
                'status' => [
                    Order::STATUS_UNDELIVER,
                    Order::STATUS_CONFIRMED,
                    Order::STATUS_DELIVERED,
                    Order::STATUS_CLOSED,
                ],
            ])
            ->andFilterWhere(['not in', 'custom_user_id', $this->getCompanyId()]);
        $membraneData = (new \yii\db\Query())
            ->select(['custom_user_id', 'total_fee'])
            ->from('{{%membrane_order}}')
            ->where(['>=', 'created_date', $dateFrom])
            ->andWhere(['<=', 'created_date', $dateTo])
            ->andWhere([
                'status' => [
                    MembraneOrder::STATUS_PAYED,
                    MembraneOrder::STATUS_ACCEPTED,
                    MembraneOrder::STATUS_FINISHED,
                ],
            ])
            ->andFilterWhere(['not in', 'custom_user_id', $this->getCompanyId()]);
        $list = (new \yii\db\Query())
            ->select([
                'custom_user_id' => 'order.custom_user_id',
                'consumption' => 'SUM(order.total_fee)',
                'account' => 'user.account',
                'mobile' => 'user.mobile',
                'nickname' => 'user.nick_name',
                'shopname' => 'user.shop_name',
            ])
            ->from(['order' => $orderData->union($membraneData, true)])
            ->innerJoin(['user' => '{{%custom_user}}'], 'order.custom_user_id = user.id')
            ->groupBy(['order.custom_user_id'])
            ->orderBy(['consumption' => SORT_DESC])
            ->all();
        $rank = [];
        foreach($list as $k => $v){
            $rank[$v['custom_user_id']] = [
                'rank' => $k + 1,
                'consumption' => $v['consumption'],
                'account' => $v['account'],
                'mobile' => $v['mobile'] ? substr($v['mobile'], 0, 3) . '****' . substr($v['mobile'], -4) : '',
                'nickname' => $v['nickname'],
                'shopname' => $v['shopname'],
            ];
        }
        return $rank;
    }

    private $_companyId;
    protected function getCompanyId(){
        if(is_null($this->_companyId)){
            $this->_companyId = Yii::$app->RQ->AR(new CustomUserAR)->column([
                'select' => ['id'],
                'where' => [
                    'level' => RegisterCode::LEVEL_COMPANY,
                ],
            ], []);
        }
        return $this->_companyId;
    }
}
