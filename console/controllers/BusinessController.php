<?php
namespace console\controllers;

use console\controllers\business\MembraneTrait;
use Yii;
use console\controllers\basic\Controller;
use console\controllers\business\CountCustomQuantityTrait;
use console\controllers\business\DayAchievement;
use console\controllers\business\WeekAchievement;
use console\controllers\business\MonthAchievement;
use console\controllers\business\WalletTrait;

/**
 * Business站所有计划任务
 */
class BusinessController extends Controller{

    use CountCustomQuantityTrait;
    use DayAchievement;
    use WeekAchievement;
    use MonthAchievement;
    use WalletTrait;
    use MembraneTrait;

}
