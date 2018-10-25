<?php
namespace console\controllers;

use Yii;
use console\controllers\basic\Controller;
use console\controllers\nanjing\QueryBalanceTrait;
use console\controllers\nanjing\AutoValidateTrait;
use console\controllers\nanjing\QueryGatewayDepositTrait;
use console\controllers\nanjing\DrawOfDrawTrait;
use console\controllers\nanjing\QueryDetailTrait;
use console\controllers\nanjing\BalanceWarningTrait;

class NanjingController extends Controller{

    use QueryBalanceTrait;
    use AutoValidateTrait;
    use QueryGatewayDepositTrait;
    use DrawOfDrawTrait;
    use QueryDetailTrait;
    use BalanceWarningTrait;
}
