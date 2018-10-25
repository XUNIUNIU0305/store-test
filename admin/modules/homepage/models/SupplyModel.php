<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-11-8
 * Time: 下午5:04
 */

namespace admin\modules\homepage\models;

use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\homepage\SupplyUser;

class SupplyModel extends Model
{
    const SCE_GET_LIST = 'get_list';

    public $name;

    public function scenarios()
    {
        return [
            self::SCE_GET_LIST => [
                'name'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['name'],
                'string',
                'message' => 9002
            ]
        ];
    }

    public function getList()
    {
        $res = SupplyUser::queryBrandByName($this->name);
        return array_map(function ($item) {
            return Handler::getMultiAttributes($item, [
                'id',
                'img' => 'header_img',
                'name'    => 'brand_name',
                'company_name'
            ]);
        }, $res);
    }
}