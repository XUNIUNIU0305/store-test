<?php
namespace common\models\parts\trade;

use Yii;
use yii\base\Object;

abstract class PayLogAbstract extends Object{

    /**
     * 获取支出金额
     *
     * @return float
     */
    abstract public function getPayAmount();

    /**
     * 获取支出前的余额
     *
     * @return float
     */
    abstract public function getRMBBefore();

    /**
     * 获取支出后的余额
     *
     * @return float
     */
    abstract public function getRMBAfter();

    /**
     * 获取支出时间
     *
     * @return string|int
     */
    abstract public function getPayTime();
}
