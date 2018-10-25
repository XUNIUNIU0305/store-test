<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-4-16
 * Time: 下午2:18
 */

namespace admin\modules\site\models\parts;

use Yii;
use common\ActiveRecord\AdminUnbindCustomMobileLogAR;
use common\ActiveRecord\AdminUserAR;
use common\components\handler\VisitorHandler;
use yii\base\Object;

class AdminUnbindCustomMobileLog extends Object
{
    private $_custom_user_id;
    private $_operatorId;
    private $_operatorAccount;

    private $_userIp;
    private $_userRequestHeader;

    const OPERATE_TYPE_UNBIND = 1;
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

    public function createLog($customUserId)
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

        if ($this->generate()) {
            return true;
        } else {
            return false;
        }
    }

    private function generate()
    {
        $log = new AdminUnbindCustomMobileLogAR();
        $log->custom_user_id = $this->_custom_user_id;
        $log->operate_type = SELF::OPERATE_TYPE_UNBIND;
        $log->user_type = SELF::OPERATE_USER_TYPE_ADMIN;
        $log->user_id = $this->_operatorId;
        $log->user_account = $this->_operatorAccount;
        $log->operate_datetime = Yii::$app->time->fullDate;
        $log->operate_unixtime = Yii::$app->time->unixTime;
        $log->user_ip = $this->_userIp;
        $log->user_request_header = $this->_userRequestHeader;
        if ($log->save()) {
            return true;
        } else {
            return false;
        }
    }

}