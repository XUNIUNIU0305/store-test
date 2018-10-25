<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-25
 * Time: 下午2:34
 */

namespace business\modules\data\models\traits;


use business\models\handler\BusinessAreaHandler;
use business\models\handler\CustomUserHandler;
use business\models\parts\Area;
use business\modules\data\models\objects\BusinessArea;

trait UserAreaTrait
{
    private $fifthArea = false;

    /**
     * 获取第五级ID
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    private function getFifthArea()
    {
        if($this->fifthArea === false){
            $this->fifthArea = BusinessAreaHandler::findAreaByLevel($this->getMainArea(), Area::LEVEL_FIFTH);
        }
        return $this->fifthArea;
    }

    private function getFifthAreaId()
    {
        return array_column($this->getFifthArea(), 'id');
    }

    private $userId = false;

    /**
     * 获取用户管理门店ID
     * @return array
     */
    private function getUserId()
    {
        if ($this->userId === false) {
            $this->userId = CustomUserHandler::findUserIdBy($this->getFifthAreaId());
        }
        return $this->userId;
    }

    private $inviteUserId = false;

    private function getInviteUserId()
    {
        if($this->inviteUserId === false){
            $this->inviteUserId = CustomUserHandler::findInviteUserIdBy($this->getFifthAreaId());
        }
        return $this->inviteUserId;
    }

    private $mainArea = false;

    /**
     * 主区域
     */
    private function getMainArea()
    {
        if ($this->mainArea === false) {
            $account = \Yii::$app->user->identity;
            $this->mainArea = new BusinessArea(['id' => $account->business_area_id]);
        }
        return $this->mainArea;
    }
}