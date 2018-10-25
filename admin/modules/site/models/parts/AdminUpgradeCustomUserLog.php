<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-4-16
 * Time: 下午2:18
 */

namespace admin\modules\site\models\parts;

use common\models\parts\custom\CustomUser;
use Yii;
use common\ActiveRecord\AdminUpgradeCustomUserLogAR;
use common\ActiveRecord\AdminUserAR;
use common\components\handler\VisitorHandler;
use yii\base\Object;

class AdminUpgradeCustomUserLog extends Object
{
    private $_custom_user_id;
    private $_operatorId;
    private $_operatorAccount;

    private $_userIp;
    private $_userRequestHeader;

    private $_operatorTypeUpgrade;
    const OPERATE_USER_TYPE_ADMIN = 1;

    public function init()
    {
        $this->_userIp = $this->getUserIp();
        $this->_userRequestHeader = $this->getUserRequestHeader();
    }

    public function getUserIp(bool $long = false)
    {
        return $long ? Yii::$app->request->userIP : ip2long(Yii::$app->request->userIP);
    }

    public function getUserRequestHeader()
    {
        return serialize(VisitorHandler::collectRequestHeader());
    }

    public function createLog($customUserId, $action, array $level)
    {
        $this->_custom_user_id = $customUserId;
        if (!is_null(Yii::$app->AdminUser)) {
            if (!is_null(Yii::$app->AdminUser->menus)) {
                if (!is_null(Yii::$app->AdminUser->menus->id)) {
                    $this->_operatorId = Yii::$app->AdminUser->menus->id;
                    $this->_operatorAccount = AdminUserAR::find()->select(['account'])->where(['id' => Yii::$app->AdminUser->menus->id])->scalar();
                    if (is_null($this->_operatorAccount)) {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
        if ($action == UserHandler::USER_UPGRADE) {
            $this->_operatorTypeUpgrade = UserHandler::USER_UPGRADE;
        } else {
            $this->_operatorTypeUpgrade = UserHandler::USER_DROPGRADE;
        }
        if ($this->generate($level)) {
            return true;
        } else {
            return false;
        }
    }

    private function generate(array $level)
    {
        $log = new AdminUpgradeCustomUserLogAR();
        $log->custom_user_id = $this->_custom_user_id;
        $log->operate_type = $this->_operatorTypeUpgrade;
        $log->user_type = SELF::OPERATE_USER_TYPE_ADMIN;
        $log->user_id = $this->_operatorId;
        $log->user_account = $this->_operatorAccount;
        $log->user_last_level = $level['lastlevel'];
        $log->user_now_level = $level['nowlevel'];
        $log->operate_datetime = Yii::$app->time->fullDate;
        $log->operate_unixtime = Yii::$app->time->unixTime;
        $log->user_ip = $this->_userIp;
        $log->user_request_header = $this->_userRequestHeader;
        if (!$log->save()) {
            return false;
        } else {
            return true;
        }
    }

}