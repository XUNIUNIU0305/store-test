<?php
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;
use common\models\parts\partner\Authorization;
use common\ActiveRecord\CustomUserAuthorizationAR;
use common\models\parts\partner\PartnerPromoter;
use yii\base\InvalidCallException;
use admin\models\parts\trade\Wallet;

class PartnerController extends Controller{

    private $_userWallet = [
        PartnerPromoter::TYPE_BUSINESS => '\business\models\parts\trade\Wallet',
        PartnerPromoter::TYPE_CUSTOM => '\custom\models\parts\trade\Wallet',
    ];

    private $_adminWallet;

    public function actionMakeAccountValid(){
        $authorizedIds = Yii::$app->RQ->AR(new CustomUserAuthorizationAR)->column([
            'select' => ['id'],
            'where' => [
                'status' => Authorization::STATUS_AUTHORIZE_SUCCESS,
            ],
        ]);
        if(empty($authorizedIds))return 0;
        foreach($authorizedIds as $authorizedId){
            $authorization = new Authorization(['id' => $authorizedId]);
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $authorization->setStatus(Authorization::STATUS_ACCOUNT_VALID);
                $this->payAward($authorization);
                $transaction->commit();
            }catch(\Exception $e){
                $transaction->rollBack();
            }
        }
        return 0;
    }

    private function payAward(Authorization $authorization, $return = 'throw'){
        if(!$authorization->awardRmb)return true;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $receiverWallet = (new \ReflectionClass($this->_userWallet[$authorization->promoterType]))->newInstance([
                'userId' => $authorization->promoterUserId,
                'receiveType' => Wallet::RECEIVE_PARTNER_AWARD,
            ]);
            if($this->getAdminWallet()->pay($authorization, $receiverWallet)){
                $transaction->commit();
                return true;
            }else{
                $transaction->rollBack();
                return Yii::$app->EC->callback($return, 'paying authorization failed');
            }
        }catch(\Exception $e){
            if($transaction->isActive){
                $transaction->rollBack();
            }
            return Yii::$app->EC->callback($return, $e);
        }
    }

    private function getAdminWallet(){
        if(is_null($this->_adminWallet)){
            $this->_adminWallet = new Wallet;
        }
        return $this->_adminWallet;
    }
}
