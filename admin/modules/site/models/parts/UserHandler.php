<?php
/**
 * Created by PhpStorm.
 * User: forrestgao
 * Date: 18-4-17
 * Time: 下午3:27
 */

namespace admin\modules\site\models\parts;

use admin\modules\site\models\UserModel;
use common\ActiveRecord\CustomUserAR;
use common\models\parts\custom\CustomUser;
use Yii;
use yii\base\Object;

class UserHandler extends Object
{
    const USER_UPGRADE = 0;
    const USER_DROPGRADE = 1;
    /*
     * 事务操作
     */
    public function cancel($customUser)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $customUser->setStatus();
            $customUser->setMobileBak($customUser->mobile);
            $customUser->setMobile(0);
            if (!$customUser->unbindUserWechat($customUser->id)) {
                throw new \Exception;
            }
            if (!(new AdminCancelCustomUserLog())->createLog($customUser->id)) {
                throw new \Exception;
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
        return true;
    }

    /**
     *解绑手机事务
     */
    public function unbind($customUser)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $customUser->setMobileBak($customUser->mobile);
            $customUser->setMobile(0);
            if (!(new AdminUnbindCustomMobileLog())->createLog($customUser->id)) {
                throw new \Exception;
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
        return true;
    }

    /**
     *升降用户事务
     */
    public function upgrade(CustomUserAR $customUser, int $action)
    {
        if ($action == SELF::USER_UPGRADE || $action == SELF::USER_DROPGRADE) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($action == SELF::USER_UPGRADE) {
                    switch ($customUser->level) {
                        case CustomUser::LEVEL_PARTNER :
                            $customUser->level = CustomUser::LEVEL_IN_SYSTEM;
                            $level = ['nowlevel' => CustomUser::LEVEL_IN_SYSTEM, 'lastlevel' => CustomUser::LEVEL_PARTNER];
                            break;
                        case CustomUser::LEVEL_IN_SYSTEM :
                            $customUser->level = CustomUser::LEVEL_COMPANY;
                            $level = ['nowlevel' => CustomUser::LEVEL_COMPANY, 'lastlevel' => CustomUser::LEVEL_IN_SYSTEM];
                            break;
                        default :
                            throw new \Exception;
                    }
                }
                if ($action == SELF::USER_DROPGRADE) {
                    if ($customUser->level == CustomUser::LEVEL_COMPANY) {
                        $customUser->level = CustomUser::LEVEL_IN_SYSTEM;
                        $level = ['nowlevel' => CustomUser::LEVEL_IN_SYSTEM, 'lastlevel' => CustomUser::LEVEL_COMPANY];
                    }elseif($customUser->level == CustomUser::LEVEL_IN_SYSTEM){
                        $customUser->level = CustomUser::LEVEL_PARTNER;
                        $level = [
                            'nowlevel' => CustomUser::LEVEL_PARTNER,
                            'lastlevel' => CustomUser::LEVEL_IN_SYSTEM,
                        ];
                    }else{
                        throw new \Exception;
                    }
                }
                if (!$customUser->save()) {
                    throw new \Exception;
                }
                if (!(new AdminUpgradeCustomUserLog())->createLog($customUser->id, $action, $level)) {
                    throw new \Exception;
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return false;
            }
        }else{
            return false;
        }

        return true;
    }

}
