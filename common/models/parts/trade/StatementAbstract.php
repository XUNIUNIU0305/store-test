<?php
namespace common\models\parts\trade;

use Yii;
use yii\base\Object;

abstract class StatementAbstract extends Object{

    //收入
    const TYPE_RECEIVE = 1;
    //支出
    const TYPE_PAY = 2;
    //冻结
    const TYPE_FREEZE = 3;
    //解冻
    const TYPE_THAW = 4;

    /**
     * 获取变更类型：收入 支出
     *
     * @return int
     */
    abstract public function getAlterationType();

    /**
     * 获取相应的日志ID
     *
     * @return int
     */
    abstract public function getLogId();

    /**
     * 获取变更金额
     *
     * @return float
     */
    abstract public function getAlterationAmount();

    /**
     * 获取变更前余额
     *
     * @return float
     */
    abstract public function getRMBBefore();

    /**
     * 获取变更后余额
     *
     * @return float
     */
    abstract public function getRMBAfter();

    /**
     * 获取变更时间
     *
     * @return string|int
     */
    abstract public function getAlterationTime();

    /**
     * 获取类型列表
     *
     * @return array
     */
    public static function getTypes(){
        return [
            self::TYPE_RECEIVE,
            self::TYPE_PAY,
            self::TYPE_FREEZE,
            self::TYPE_THAW,
        ];
    }
}
