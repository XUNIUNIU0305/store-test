<?php

namespace console\controllers;

use yii\console\Controller;
use console\models\groupbuy;

/**
 * 拼团返现
 */
class GroupbuyController extends Controller
{
    private $calculate;
    private $cashback;
    private $logger;
    private $dbHandler;
    /**
     * 开始返现
     */
    public function actionCashback()
    {
        if($this->calculate->calculate($this) === false) {
            return 1;
        }
        if($this->cashback->cashback($this) === false) {
            return 1;
        }
        return 0;
    }
    
    /**
     * 导出Excel
     */
    public function actionExport()
    {
        if($this->cashback->export($this) === false) {
            return 1;
        }
        return 0;
    }    
    public function init()
    {
        $this->logger       = new groupbuy\CashBackLogger();
        $this->dbHandler    = new groupbuy\GroupbuyOrder();        
        $this->calculate    = new groupbuy\CalculateGroupbuyCashBackLevel();
        $this->cashback     = new groupbuy\CashBack();
        
        $this->logger->setLoggerInto($this->calculate, $this->cashback);
        $this->dbHandler->setDbInto($this->calculate, $this->cashback, $this->logger);
    }
}