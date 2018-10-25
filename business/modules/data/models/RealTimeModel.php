<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-23
 * Time: 下午3:11
 */

namespace business\modules\data\models;


class RealTimeModel extends DayModel
{
    public function init()
    {
        $this->start = date('Y-m-d');
        $this->end = $this->start . ' 23:59:59';
    }
}