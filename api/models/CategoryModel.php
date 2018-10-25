<?php
namespace api\models;

use Yii;
use common\models\Model;
use common\models\parts\ProductCategory;

class CategoryModel extends Model{

    public $parent_category_id;

    const SCE_GET_CATEGORY = 'get_category';

    public function scenarios(){
        return [
            self::SCE_GET_CATEGORY => [
                'parent_category_id',
            ],
        ];
    }

    public function rules(){
        return [
            [
                ['parent_category_id'],
                'default',
                'value' => 0,
            ],
            [
                ['parent_category_id'],
                'required',
                'message' => 9001,
            ],
            [
                ['parent_category_id'],
                'common\validators\category\ParentIdValidator',
                'message' => 7021,
            ],
        ];
    }

    public function getCategory(){
        return ProductCategory::getChildCategory($this->parent_category_id);
    }
}
