<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-16
 * Time: 下午2:23
 */

namespace common\models\temp;


use common\models\Model;

class PostModel extends Model
{
    const SCE_LIST = 'get';

    public function scenarios()
    {
        return [
            self::SCE_LIST => []
        ];
    }

    public function rules()
    {
        return [];
    }

    public function get()
    {
        return [
            'post' => ''
        ];
    }
}
