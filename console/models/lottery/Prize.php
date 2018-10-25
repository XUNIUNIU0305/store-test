<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-9-30
 * Time: 下午3:24
 */

namespace console\models\lottery;


/**
 * Class Prize
 * @package console\models\lottery
 * @property $max
 * @property $winn
 */
class Prize extends \common\models\lottery\Prize
{
    /**
     * 最大中奖数
     * @var int
     */
    public $max = 0;

    /**
     * 已中奖数
     * @var int
     */
    public $winn = 0;

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->winn >= $this->num;
    }
}