<?php
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;
use common\models\parts\gpubs\GpubsGroup;
use common\ActiveRecord\ActivityGpubsGroupAR;
use admin\modules\fund\models\parts\DepositAndDrawTicket;
use admin\models\parts\role\AdminAccount;
use common\models\parts\custom\CustomUser;

class GpubsController extends Controller{

    public function actionCancelExpireGroup(){
        $expireGroupIds = Yii::$app->RQ->AR(new ActivityGpubsGroupAR)->column([
            'select' => ['id'],
            'where' => ['<=', 'group_end_unixtime', time()],
            'andWhere' => [
                'status' => [
                    GpubsGroup::STATUS_UNPAID, 
                    GpubsGroup::STATUS_WAIT
                ],
            ],
        ]);
        foreach($expireGroupIds as $id){
            $group = new GpubsGroup([
                'id' => $id,
            ]);
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $group->setStatus(GpubsGroup::STATUS_CANCELED);
                $transaction->commit();
            }catch(\Exception $e){
                $transaction->rollBack();
                continue;
            }
        }
        return 0;
    }

    public function actionForceCancelGroup($groupNumber){
        $groupId = Yii::$app->RQ->AR(new ActivityGpubsGroupAR)->scalar([
            'select' => ['id'],
            'where' => [
                'group_number' => $groupNumber,
            ],
        ]);
        if(!$groupId){
            $this->stdout('未查找到该团号' . PHP_EOL);
            return 0;
        }
        $group = new GpubsGroup([
            'id' => $groupId,
        ]);
        if(!in_array($group->status, [GpubsGroup::STATUS_UNPAID, GpubsGroup::STATUS_WAIT])){
            $this->stdout('团状态错误' . PHP_EOL);
            switch($group->status){
                case GpubsGroup::STATUS_UNPAID:
                    $status = '未成单';
                    break;

                case GpubsGroup::STATUS_WAIT:
                    $status = '拼购中';
                    break;

                case GpubsGroup::STATUS_ESTABLISH:
                    $status = '已成团';
                    break;

                case GpubsGroup::STATUS_CANCELED:
                    $status = '已取消';
                    break;

                default:
                    $status = '未知';
                    break;
            }
            $this->stdout('当前团状态：' . $status . PHP_EOL);
            return 0;
        }
        if($group->setCanceled(false)){
            $this->stdout('强制取消成功！' . PHP_EOL);
            return 0;
        }else{
            $this->stdout('强制取消失败' . PHP_EOL);
            return 0;
        }
    }

    /**
     * common\models\parts\gpubs\GpubsGroupDetail::setStatus() 屏蔽限制
     */
    public function actionForceCancelActivity($activityGpubsProductId, string $reclaim = ''){
        $groupNumbers = ActivityGpubsGroupAR::find()->
            select(['group_number'])->
            where(['activity_gpubs_product_id' => $activityGpubsProductId])->
            andWhere(['<>', 'status', GpubsGroup::STATUS_CANCELED])->
            column();
        if(!$groupNumbers){
            $this->stdout('无团信息' . PHP_EOL);
            return 0;
        }
        foreach($groupNumbers as $groupNumber){
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $group = new GpubsGroup([
                    'groupNumber' => $groupNumber,
                ]);
                $group->setCanceled();
                $groupAR = ActivityGpubsGroupAR::findOne([
                    'group_number' => $groupNumber,
                ]);
                Yii::$app->RQ->AR($groupAR)->update([
                    'group_establish_datetime' => '0000-01-01 00:00:00',
                    'group_establish_unixtime' => 0,
                ]);
                if($reclaim === 'true'){
                    $this->recliamFundToGroupOwner($group);
                }
                $transaction->commit();
                $this->stdout("团：{$groupNumber} 取消成功。" . PHP_EOL);
            }catch(\Exception $e){
                $transaction->rollBack();
                $this->stdout("团：{$groupNumber} 取消失败！" . PHP_EOL);
                continue;
            }
        }
        $this->stdout('操作完成。' . PHP_EOL);
        return 0;
    }

    /**
     * admin\modules\fund\models\parts\DepositAndDrawTicket::recordOperation() 修改ip|header，登录验证
     */
    protected function recliamFundToGroupOwner(GpubsGroup $group){
        foreach($group->getDetail() as $detail){
            if($detail->own_user_id != $detail->custom_user_id){
                $this->fundOut($detail->getCustomUser(), (float)$detail->total_fee);
                $this->fundIn(new CustomUser(['id' => $detail->own_user_id]), (float)$detail->total_fee);
            }
        }
    }

    protected function fundOut(CustomUser $user, float $rmb){
        $adminAccount = new AdminAccount([
            'id' => 1,
        ]);
        return DepositAndDrawTicket::generate([
            'operateUser' => $adminAccount,
            'targetUser' => $user,
            'operateType' => DepositAndDrawTicket::OPERATE_TYPE_DRAW,
            'amount' => $rmb,
            'operateBrief' => '拼购金额返还至团长账户',
            'operateDetail' => '拼购金额返还至团长账户',
        ]);
    }

    protected function fundIn(CustomUser $user, float $rmb){
        $adminAccount = new AdminAccount([
            'id' => 1,
        ]);
        return DepositAndDrawTicket::generate([
            'operateUser' => $adminAccount,
            'targetUser' => $user,
            'operateType' => DepositAndDrawTicket::OPERATE_TYPE_DEPOSIT,
            'amount' => $rmb,
            'operateBrief' => '拼购金额返还',
            'operateDetail' => '拼购金额返还',
        ]);
    }
}
