<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-28
 * Time: 下午3:38
 */

namespace business\modules\data\models\traits;


trait AutoSplitDateTrait
{
    /**
     * 拆分时间分组
     * @return array
     */
    public function autoSplitDate()
    {
        $res = [];
        $date = new \DateTime($this->start);
        if($this->by === 'month')
            $date = new \DateTime($date->format('Y-m-' . '01'));
        $end = new \DateTime($this->end);
        while ($date < $end){
            $val = [
                'date' => $date->format('Y-m-d H:i:s')
            ];
            switch ($this->by){
                case 'hour':
                    $val['key'] = $date->format('m-d H:00');
                    $date = $date->modify('+1 hour');
                    break;
                case 'week':
                    $val['key'] = $date->format('m-d') . '当周';
                    $date = $date->modify('+1 week');
                    break;
                case 'month':
                    $val['key'] = $date->format('m') . '月';
                    $date = $date->modify('+1 month');
                    break;
                default:
                    $val['key'] = $date->format('m-d');
                    $date = $date->modify('+1 day');
            }
            $res[] = $val;
            if (count($res) > 20) break;
        }
        return $res;
    }
}