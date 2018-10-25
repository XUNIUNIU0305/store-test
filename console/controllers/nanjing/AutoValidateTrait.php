<?php
namespace console\controllers\nanjing;

use Yii;
use common\models\parts\trade\recharge\nanjing\Nanjing;
use common\models\parts\trade\recharge\nanjing\account\AccountAbstract;
use common\models\parts\trade\recharge\nanjing\draw\DrawTicket;
use common\ActiveRecord\UserDrawAR;

trait AutoValidateTrait{

    public function actionAutoValidate(){
        Yii::$app->db->queryMaster = true;
        $invalidateBusinessDrawTicketId = Yii::$app->RQ->AR(new UserDrawAR)->scalar([
            'select' => ['id'],
            'where' => [
                'user_type' => AccountAbstract::ACCOUNT_TYPE_BUSINESS,
                'status' => DrawTicket::STATUS_APPLY,
            ],
            'limit' => 1,
            'orderBy' => [
                'id' => SORT_ASC,
            ],
        ]);
        if($invalidateBusinessDrawTicketId){
            $nanjing = new Nanjing;
            $drawTicket = new DrawTicket([
                'id' => $invalidateBusinessDrawTicketId,
            ]);
            try{
                $result = $nanjing->transOfDraw($drawTicket);
                if($result !== true)throw new \Exception;
            }catch(\Exception $e){}
        }
        return 0;
    }
}
