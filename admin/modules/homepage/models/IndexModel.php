<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-31
 * Time: 上午11:48
 */

namespace admin\modules\homepage\models;


use common\ActiveRecord\SupplyUserAR;
use common\models\Model;

class IndexModel extends Model
{
    const SCE_SEARCH_BRAND = 'search_brand';

    public $brand_name;
    public $limit = 10;

    public function scenarios()
    {
        return [
            self::SCE_SEARCH_BRAND => [
                'brand_name'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['brand_name'],
                'string',
                'message' => 9002
            ],
            [
                ['limit'],
                'integer',
                'min' => 1,
                'message' => 9002
            ]
        ];
    }

    /**
     * 品牌搜索
     * @return array|\yii\db\ActiveRecord[]
     */
    public function searchBrand()
    {
        return SupplyUserAR::find()
            ->select(['id', 'brand_name', 'account'])
            ->filterWhere(['like', 'brand_name', $this->brand_name])
            ->limit($this->limit)
            ->asArray()->all();
    }
}