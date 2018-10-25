<?php
namespace common\models\parts\trade\recharge\nanjing\draw;

use Yii;
use common\components\handler\Handler;
use yii\data\ActiveDataProvider;
use common\ActiveRecord\UserDrawAR;
use common\models\parts\trade\recharge\nanjing\account\AccountAbstract;
use common\models\parts\trade\recharge\nanjing\handler\SeqNoGenerator;

class DrawTicketHandler extends Handler{

    public static function create(float $rmb, AccountAbstract $account, $return = 'throw'){
        if($rmb <= 0)return Yii::$app->EC->callback($return, 'P_float');
        if(!$nanjingAccount = $account->getNanjingAccount(false))return Yii::$app->EC->callback('Nanjing Account missing');
        $seqNoGenerator = new SeqNoGenerator(['account' => $account]);
        $drawNumber = $seqNoGenerator->getId();
        try{
            $insertId = Yii::$app->RQ->AR(new UserDrawAR)->insert([
                'draw_number' => $drawNumber,
                'rmb' => $rmb,
                'user_id' => $account->id,
                'user_type' => $account->getUserType(),
                'nanjing_account_id' => $nanjingAccount->id,
                'apply_datetime' => Yii::$app->time->fullDate,
                'apply_unixtime' => Yii::$app->time->unixTime,
            ]);
            $drawTicket = new DrawTicket(['id' => $insertId]);
            $account->wallet->freeze($drawTicket);
            return $drawTicket;
        }catch(\Exception $e){
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public static function provide(AccountAbstract $account = null, array $status = null, int $currentPage = 1, int $pageSize = 20){
        if(is_null($account)){
            $query = UserDrawAR::find()->
                filterWhere(['status' => $status]);
        }else{
            $query = UserDrawAR::find()->
                select(['id'])->
                where(['user_id' => $account->id])->
                andWhere(['user_type' => $account->userType])->
                andFilterWhere(['status' => $status]);
        }
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);
    }
}
