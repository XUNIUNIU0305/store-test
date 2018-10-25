<?php
namespace business\models\handler;

use Yii;
use common\components\handler\Handler;
use common\ActiveRecord\BusinessUserAR;
use common\ActiveRecord\BusinessRoleAR;
use common\ActiveRecord\BusinessUserWalletAR;
use business\models\parts\Account;
use business\models\parts\Area;
use business\models\parts\Role;
use yii\data\ActiveDataProvider;

class AccountHandler extends Handler{

    public static function create(string $name, string $remark, $return = 'throw'){
        if(Yii::$app->user->isGuest)return Yii::$app->EC->callback($return, 'the user who creating account must login');
        if(empty($name))return Yii::$app->EC->callback($return, 'unavailable name');
        do{
            $account = rand(10000000, 99999999);
        }while(BusinessUserAR::findOne(['account' => $account]));
        $role = new Role(['id' => Role::UNDEFINED]);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $userId = Yii::$app->RQ->AR(new BusinessUserAR)->insert([
                'account' => $account,
                'passwd' => '',
                'name' => $name,
                'mobile' => 0,
                'remark' => $remark,
                'business_role_id' => $role->role,
                'business_area_id' => 0,
                'top_business_area_id' => Yii::$app->BusinessUser->account->topArea->id,
                'level' => $role->level,
                'status' => Account::STATUS_UNREGISTERED,
            ]);
            Yii::$app->RQ->AR(new BusinessUserWalletAR)->insert([
                'business_user_id' => $userId,
            ]);
            $transaction->commit();
            return new Account(['id' => $userId]);
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, $e);
        }
    }

    public static function provide(string $search, int $currentPage, int $pageSize, $return = 'throw'){
        if(Yii::$app->user->isGuest)return Yii::$app->EC->callback($return, 'user must login');
        if($currentPage <= 0)$currentPage = 1;
        if($pageSize <= 0)$pageSize = 1;
        if(empty($search)){
            $accountFilter = ['name' => null];
        }else{
            if(is_numeric($search)){
                switch(strlen($search)){
                    case 8:
                        $accountFilter = ['account' => $search];
                        break;

                    case 11:
                        $accountFilter = ['mobile' => $search];
                        break;

                    default:
                        $accountFilter = ['like', 'name', $search];
                        break;
                }
            }else{
                $accountFilter = ['like', 'name', $search];
            }
        }
        $areaFilter = ($topAreaId = Yii::$app->BusinessUser->account->topArea->id) ? [$topAreaId, Area::LEVEL_UNDEFINED] : null;
        return new ActiveDataProvider([
            'query' => BusinessUserAR::find()->
                select(['id'])->
                filterWhere($accountFilter)->
                andWhere(['<', 'level', Yii::$app->BusinessUser->account->level])->
                andWhere([
                    'status' => [
                    Account::STATUS_NORMAL,
                    Account::STATUS_UNREGISTERED,
                ]])->
                andWhere(['not in', 'business_role_id', [Role::SUPER_ADMIN, Role::ADMIN]])->
                andFilterWhere(['top_business_area_id' => $areaFilter]),
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

    public static function remove(Account $account, $return = 'throw'){
        if($account->status == Account::STATUS_REMOVE)return Yii::$app->EC->callback($return, 'the user has been removed');
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if($account->area->canModify){
                $account->resetRole();
            }
            $account->setStatus(Account::STATUS_REMOVE);
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            return Yii::$app->EC->callback($return, 'mysql');
        }
    }
}
