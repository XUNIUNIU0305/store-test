<?php
namespace admin\components\handler;

use Yii;
use common\components\handler\Handler;
use common\ActiveRecord\ProductCategoryAR;
use common\models\parts\ProductCategory;
use common\traits\ErrCallbackTrait;

class ProductCategoryHandler extends Handler{

    use ErrCallbackTrait;

    public static function create(int $parentId = ProductCategoryAR::TOP_CATEGORY_ID, string $categoryName, $return = 'throw'){
        if(empty($categoryName))return $this->errCallback($return, 'string');
        if($parentId === ProductCategoryAR::TOP_CATEGORY_ID){
            $categoryId = Yii::$app->RQ->AR(new ProductCategoryAR)->insert([
                'parent_id' => $parentId,
                'title' => $categoryName,
                'is_end' => ProductCategoryAR::PARENT_CATEGORY,
                'display' => ProductCategory::STATUS_DISPLAY,
            ], $return);
            return $categoryId ? new ProductCategory(['id' => $categoryId]) : $categoryId;
        }else{
            try{
                $categoryObj = new ProductCategory(['id' => $parentId]);
                $categoryObj->addChildCategory($categoryName);
                return new ProductCategory(['id' => Yii::$app->db->lastInsertId]);
            }catch(\Exception $e){
                return $this->errCallback($return, $e);
            }
        }
    }
}
