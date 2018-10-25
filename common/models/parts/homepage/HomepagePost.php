<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-11-2
 * Time: 上午10:57
 */

namespace common\models\parts\homepage;


use common\ActiveRecord\HomepagePostAR;
use common\models\Object;

/**
 * Class HomepagePost
 * @package common\models\parts\homepage
 * @property $type
 */
class HomepagePost extends Object
{
    public static $typeList = [
        HomepagePostAR::TYPE_POST,
        HomepagePostAR::TYPE_URL
    ];

    public static $typeLabel = [
        HomepagePostAR::TYPE_POST => '公告',
        HomepagePostAR::TYPE_URL => '链接'
    ];

    /**
     * @return mixed|string
     */
    public function getTypeLabel()
    {
        return static::$typeLabel[$this->type] ?? '';
    }
}