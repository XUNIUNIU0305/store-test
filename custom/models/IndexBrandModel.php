<?php
namespace custom\models;

use common\ActiveRecord\HomepageBrandAR;
use common\models\Model;
use Yii;

class IndexBrandModel extends Model
{
    const BR_GET_BRANDS = 'get_brands';


    public function scenarios()
    {
        return [
            self::BR_GET_BRANDS => [],
        ];
    }

    public function rules()
    {
        return [];
    }

    /*
     * 获得所有楼层的所有分组的所有商品
     * 
     * @return Array
     */
    public function getBrands()
    {
        if(false === $result = Yii::$app->RQ->AR(new HomepageBrandAR())->all([
            'select' => [
                'id',
                'name',
                'brand_id',
                'company_name',
                'logo_name'
            ],
            'orderBy' => [
                'sort' => SORT_ASC
            ]
        ])) {
            $this->addError('getFloors', 5199);
            return false;
        }

        foreach($result as $k => $v) {
            if(is_array($v) && array_key_exists('logo_name', $v)) {
                $result[$k]['logo_name'] = Yii::$app->params['OSS_PostHost'] . '/' . $v['logo_name'];
            }
        }

        return $result;
    }
}
