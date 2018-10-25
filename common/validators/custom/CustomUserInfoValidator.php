<?php
namespace common\validators\custom;

use admin\modules\site\models\parts\UserHandler;
use admin\modules\site\models\UserModel;
use common\models\parts\custom\CustomUser;
use Yii;
use common\models\Validator;


class CustomUserInfoValidator extends Validator
{

    public $message;
    public $upmessage;
    public $dropmessage;
    public $mobilemessage;
    public $actionmessage;
    public $action;
    public $mobile;

    protected function validateValue($customId)
    {
        try {
            $customUser = new CustomUser(['id' => $customId]);
        } catch (\Exception $e) {
            return $this->message;
        }
        /**
         * 判断用户能否升级或降级
         */
        if (!is_null($this->action)) {
            if ($this->action == UserHandler::USER_UPGRADE || $this->action == UserHandler::USER_DROPGRADE) {
                if ($this->action == UserHandler::USER_UPGRADE && ($customUser->level >= CustomUser::LEVEL_COMPANY || $customUser->level < CustomUser::LEVEL_PARTNER)) {
                    return $this->upmessage;
                }
                if ($this->action == UserHandler::USER_DROPGRADE && $customUser->level == CustomUser::LEVEL_PARTNER) {
                    return $this->dropmessage;
                }
            } else {
                return $this->actionmessage;
            }
        }
        /**
         * 验证用户是否已经绑定有手机号
         */
        if (!is_null($this->mobile)) {
            if (!$customUser->mobile) {
                return $this->mobilemessage;
            }
        }
        return true;
    }
}
