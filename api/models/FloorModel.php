<?php
namespace api\models;

use common\ActiveRecord\AdminFloorAR;
use common\ActiveRecord\AdminFloorGoodsAR;
use common\ActiveRecord\AdminFloorGroupAR;
use common\ActiveRecord\ProductAR;
use common\models\Model;
use Yii;

class FloorModel extends Model
{
    const FL_GET_FLOORS = 'get_floors';


    public function scenarios()
    {
        return [
            self::FL_GET_FLOORS => [],
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
    public function getFloors()
    {
        if(false === $result = Yii::$app->RQ->AR(new AdminFloorAR())->all([
            'select' => [
                'id',
                'color',
                'url',
                'name',
                'status as show',
                'type'
            ]
        ])) {
            $this->addError('getFloors', 7200);
            return false;
        }
        foreach($result as $key => $column) {
            if(!$column['id']) {
                $this->addError('getFloors', 7200);
                return false;
            }
            $result[$key]['group'] = Yii::$app->RQ->AR(new AdminFloorGroupAR())->all([
                'where' => ['floor_id' => $column['id']],
                'select' => [
                    'id',
                    'name',
                ]
            ]);
            foreach($result[$key]['group'] as $keyOfGroup => $group) {                
                $result[$key]['group'][$keyOfGroup]['product'] = AdminFloorGoodsAR::find()->select([
                    'pf_admin_floor_goods.id',
                    'pf_admin_floor_goods.index_image',
                    'pf_admin_floor_goods.title',
                    'pf_admin_floor_goods.sell_point',
                    'pf_admin_floor_goods.original_id',
                    'pf_product.min_price'
                ])->where(['gid' => $group['id']])->leftJoin('pf_product', '`pf_admin_floor_goods`.`original_id` = `pf_product`.`id`')
                ->orderBy(['sort' => SORT_ASC])->asArray()->all();
            }
        }
        return $result;
    }
}
