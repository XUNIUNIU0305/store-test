<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/19 0019
 * Time: 16:00
 */

namespace wechat\models;


use custom\models\IndexModel;

class ProfileModel extends \custom\modules\account\models\ProfileModel
{
    const SCE_GET_INFO = 'get_info';

    public function scenarios(){
        return [
            self::SCE_GET_INFO => []
        ];
    }

    public function getInfo()
    {
        return IndexModel::getUserInfo();
    }
}