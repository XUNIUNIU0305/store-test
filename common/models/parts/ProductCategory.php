<?php
namespace common\models\parts;

use Yii;
use yii\base\Object;
use common\ActiveRecord\ProductCategoryAR;
use common\ActiveRecord\ProductCategoryAttributeAR;
use yii\base\InvalidConfigException;
use common\traits\ErrCallbackTrait;
use common\models\parts\CategoryAttribute;

class ProductCategory extends Object{

    use ErrCallbackTrait;

    const STATUS_DISPLAY = 1;
    const STATUS_HIDE = 0;

    public $id;

    protected $AR;

    public function init(){
        if(!$this->id ||
            (!$this->AR = ProductCategoryAR::findOne($this->id))
        )throw new InvalidConfigException('unavailable product category id');
    }

    /**
     * 获取分类名称
     *
     * @return string
     */
    public function getTitle(){
        return $this->AR->title;
    }

    /**
     * 获取当前分类是否是最终分类
     *
     * @return boolean
     */
    public function getIsEnd(){
        return $this->AR->is_end ? true : false;
    }

    /**
     * 获取当前分类是否显示
     *
     * @return boolean
     */
    public function getIsDisplay(){
        return $this->AR->display == self::STATUS_DISPLAY;
    }

    /**
     * 获取当前分类的上级分类ID
     *
     * @return integer
     */
    public function getParentId(){
        return $this->AR->parent_id;
    }

    public function getParentCategory(){
        if($this->getParentId()){
            return new ProductCategory(['id' => $this->getParentId()]);
        }else{
            return false;
        }
    }

    /**
     * 获取当前分类的属性
     *
     * @return Object CategoryAttribute
     */
    public function getAttributes(){
        return new CategoryAttribute(['categoryId' => $this->id]);
    }

    /**
     * 设置当前分类的名称
     *
     * @param string $title 名称
     * @param mix $return 错误回调
     *
     * @return true|mix
     */
    public function setTitle(string $title, $return = 'throw'){
        if(empty($title))return $this->errCallback($return, 'require non empty string');
        $this->AR->title = $title;
        if($this->AR->update() !== false){
            return true;
        }else{
            return $this->errCallback($return, 'setting title failed');
        }
    }

    /**
     * 设置分类状态；显示or不显示
     *
     * @param integer $status 状态
     * @param mix $return 错误回调
     *
     * @return true|mix
     */
    public function setStatus($status, $return = 'throw'){
        if(in_array($status, [self::STATUS_HIDE, self::STATUS_DISPLAY])){
            return Yii::$app->RQ->AR($this->AR)->update([
                'display' => $status,
            ], $return) === $return ? $return : true;
        }else{
            return $this->errCallback($return, 'undefined status');
        }
    }

    /**
     * 获取当前分类是否存在指定属性
     *
     * @param Attribute $attribute 属性对象
     *
     * @return boolean
     */
    public function isExistAttribute(Attribute $attribute){
        return Yii::$app->RQ->AR(new ProductCategoryAttributeAR)->exists([
            'where' => [
                'product_category_id' => $this->id,
                'product_spu_attribute_id' => $attribute->id,
            ],
            'limit' => 1,
        ]);
    }

    /**
     * 当前分类添加属性
     *
     * @param Attribute $attribute 属性对象
     * @param mix $return 错误回调
     *
     * @return integer|mix
     */
    public function addAttribute(Attribute $attribute, $return = 'throw'){
        if($this->isExistAttribute($attribute))return $this->errCallback($return, 'the attribute is exist');
        return Yii::$app->RQ->AR(new ProductCategoryAttributeAR)->insert([
            'product_category_id' => $this->id,
            'product_spu_attribute_id' => $attribute->id,
        ], $return);
    }

    /**
     * 当前分类删除属性
     *
     * @param Attribute $attribute 属性对象
     * @param mix $return 错误回调
     *
     * @return integer
     */
    public function removeAttribute(Attribute $attribute, $return = 'throw'){
        if(!$this->isExistAttribute($attribute))return $this->errCallback($return, 'the attribute is not exist');
        return Yii::$app->RQ->AR(ProductCategoryAttributeAR::findOne($attribute->id))->delete($return);
    }

    /**
     * 当前分类添加子分类
     *
     * @param string $categoryName 分类名称
     * @param mix $return 错误回调
     *
     * @return integer|mix
     */
    public function addChildCategory(string $categoryName, $return = 'throw'){
        if(empty($categoryName))return $this->errCallback($return, 'require non empty string');
        if(!$this->parentId){
            $isEnd = ProductCategoryAR::PARENT_CATEGORY;
        }else{
            if($this->isEnd){
                return $this->errCallback($return, 'the final category can not add child category');
            }else{
                $isEnd = ProductCategoryAR::END_CATEGORY;
            }
        }
        return Yii::$app->RQ->AR(new ProductCategoryAR)->insert([
            'parent_id' => $this->id,
            'title' => $categoryName,
            'is_end' => $isEnd,
            'display' => self::STATUS_DISPLAY,
        ], $return);
    }

    /**
     * 添加顶级分类
     *
     * @param string $categoryName 分类名称
     * @param mix $return 错误回调
     *
     * @return integer|mix
     */
    public static function addTopCategory(string $categoryName, $return = 'throw'){
        if(empty($categoryName))return $this->errCallback($return, 'require non empty string');
        return Yii::$app->RQ->AR(new ProductCategoryAR)->insert([
            'parent_id' => ProductCategoryAR::TOP_CATEGORY_ID,
            'title' => $categoryName,
            'is_end' => ProductCategoryAR::PARENT_CATEGORY,
            'display' => self::STATUS_DISPLAY,
        ], $return);
    }

    /**
     * 判断是否为非终端分类
     *
     * @return boolean
     */
    public static function isParentCategory($categoryId){
        if($categoryId == ProductCategoryAR::TOP_CATEGORY_ID)return true;
        return ProductCategoryAR::find()->where(['id' => $categoryId, 'is_end' => ProductCategoryAR::PARENT_CATEGORY])->exists();
    }

    /**
     * 获取次级分类
     *
     * @return array
     */
    public static function getChildCategory($parentId){
        $parentId = (int)$parentId;
        $childCategory = Yii::$app->RQ->AR(new ProductCategoryAR)->all([
            'select' => ['id', 'title', 'is_end'],
            'where' => [
                'parent_id' => $parentId,
                'display' => self::STATUS_DISPLAY,
            ],
            'orderBy' => [
                'sort' => SORT_ASC,
            ],
        ]);
        return array_map(function($category){
            $category['is_end'] = $category['is_end'] ? true : false;
            return $category;
        }, $childCategory);
    }

    /**
     * 判断终端分类是否存在
     *
     * @return boolean
     */
    public static function existEndCategory($categoryId){
        if(!(int)$categoryId)return false;
        return ProductCategoryAR::find()->where(['id' => $categoryId, 'is_end' => ProductCategoryAR::END_CATEGORY])->exists();
    }


    /**
     *====================================================
     * @param $keyword
     * @return mixed
     * @author shuang.li
     * 设置分类关键字
     *====================================================
     */
    public function setKeyword($keyword){
        $this->AR->keyword = $keyword;
        return $this->AR->update();
    }

    /**
     *====================================================
     * @return mixed
     * @author shuang.li
     * 获取分类关键字
     *====================================================
     */
    public function getKeyword(){
        return $this->AR->keyword;
    }


}
