<?php
namespace api\models;

use Yii;
use common\models\Model;
use common\models\parts\ProductCategory;
use common\ActiveRecord\HomepageColumnAR;
use common\ActiveRecord\HomepageColumnBrandAR;
use common\ActiveRecord\HomepageColumnItemAR;

class CatelogModel extends Model
{
    public $column_id;
    
    const SCE_GET_COLUMNS       = 'get_columns';
    const SCE_GET_BRANDS        = 'get_brands';
    const SCE_GET_ITEMS         = 'get_items';

    public function scenarios(){
        return [
            self::SCE_GET_COLUMNS => [],
            self::SCE_GET_BRANDS => ['column_id'],
            self::SCE_GET_ITEMS => ['column_id'],
        ];
    }

    public function rules(){
        return [
            [
                ['column_id'],
                'required',
                'message' => 9001
            ],
            [
                ['column_id'],
                'number',
                'integerOnly' => true,
                'message' => 5098,
            ],
        ];
    }
    
    /**
     * 首页控制一级目录
     * 
     * @return Array
     */
    public function getColumns()
    {
        if(false === $result = Yii::$app->RQ->AR(new HomepageColumnAR())->all([
            'select' => [
                'id',
                'name',
            ]
        ])) {
            $this->addError('getColumns', 7200);
            return false;
        }
        foreach($result as $key => $column) {
            if(!$column['id']) {
                $this->addError('getColumns', 7200);
                return false;
            }
            $result[$key]['brand'] = Yii::$app->RQ->AR(new HomepageColumnBrandAR())->all([
                'where' => ['column_id' => $column['id']],
                'select' => [
                    'brand_id',
                    'brand_name',
                    'img',
                ]
            ]);
            $result[$key]['item'] = Yii::$app->RQ->AR(new HomepageColumnItemAR())->all([
                'where' => ['column_id' => $column['id']],
                'select' => [
                    'name',
                    'img',
                    'cate_id'
                ]
            ]);
            foreach($result[$key]['brand'] as $k => $v) {
                if(is_array($v) && key_exists('img', $v)) {
                    $result[$key]['brand'][$k]['img'] = Yii::$app->params['OSS_PostHost'] . '/' . $v['img'];
                }  
            }
            foreach($result[$key]['item'] as $k => $v) {
                if(is_array($v) && key_exists('img', $v)) {
                    $result[$key]['item'][$k]['img'] = Yii::$app->params['OSS_PostHost'] . '/' . $v['img'];
                }  
            }
        }

        return $result;
    }
    
    /**
     * 首页控制二级品牌目录
     * 
     * @return Array
     */
    public function getBrands()
    {
        if(false === $result = Yii::$app->RQ->AR(new HomepageColumnBrandAR())->all([
            'where' => ['column_id' => $this->column_id],
            'select' => [
                'brand_id',
                'brand_name',
                'img',
            ]
        ])) {
            $this->addError('getColumns', 7200);
            return false;
        }
        return $result;
    }
    
    /**
     * 首页控制二级目录
     * 
     * @return Array
     */
    public function getItems()
    {
        if(false === $result = Yii::$app->RQ->AR(new HomepageColumnItemAR())->all([
            'where' => ['column_id' => $this->column_id],
            'select' => [
                'name',
                'img',
                'cate_id'
            ]
        ])) {
            $this->addError('getColumns', 7200);
            return false;
        }
        return $result;
    }
}

