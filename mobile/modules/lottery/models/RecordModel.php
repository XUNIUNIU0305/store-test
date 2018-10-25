<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-10
 * Time: 下午2:31
 */

namespace mobile\modules\lottery\models;

use common\models\Model;
use mobile\modules\lottery\models\lottery\User;

class RecordModel extends Model
{
    const SCE_WINNING = 'winning';
    const SCE_ARMS = 'arms';

    public function scenarios()
    {
        return [
            self::SCE_WINNING => [],
            self::SCE_ARMS => []
        ];
    }

    /**
     * 获取中奖记录
     */
    public function winning()
    {
        $user = new User(['ar' => \Yii::$app->user->identity]);
        return $user->getWinning();
    }

    /**
     * 获取抽奖机会
     * @return array
     */
    public function arms()
    {
        $user = new User(['ar' => \Yii::$app->user->identity]);
        return $user->getArms();
    }
}