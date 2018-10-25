<?php
namespace admin\modules\site\models\floor;
//楼层构造器

use yii\base\Object;

class FloorBuilder extends Object {

    public $id;//楼层id
    public $name ;//名称
    public $url;//链接
    public $color;//颜色
    public $type;//颜色

    public $group_id;//组id
    public $group_name;//组名

    public $product_id;//商品id
    public $original_product_id;//原商品id
    public $product_title;//商品标题
    public $product_sell_point;//商品卖点
    public $product_view_image;//商品展示图片
    public $product_index_image;//商品首页图片

    /**
     *====================================================
     * 添加商品id
     * @param $product_id
     * @return $this
     * @author shuang.li
     *====================================================
     */
    public function addProductId($product_id){
        $this->product_id = $product_id;
        return $this;
    }

    /**
     *====================================================
     * 添加商品原id
     * @param $original_product_id
     * @return $this
     * @author shuang.li
     *====================================================
     */
    public function addOriginalProductId($original_product_id){
        $this->original_product_id = $original_product_id;
        return $this;
    }


    /**
     *====================================================
     * 商品标题
     * @param $product_title
     * @return $this
     * @author shuang.li
     *====================================================
     */
    public function addProductTitle($product_title){
        $this->product_title = $product_title;
        return $this;
    }

    /**
     *====================================================
     * 商品卖点
     * @param $product_sell_point
     * @return $this
     * @author shuang.li
     *====================================================
     */
    public function addProductSellPoint($product_sell_point){
        $this->product_sell_point = $product_sell_point;
        return $this;
    }

    /**
     *====================================================
     * 商品展示图
     * @param $product_view_image
     * @return $this
     * @author shuang.li
     *====================================================
     */
    public function addProductViewImage($product_view_image){
        $this->product_view_image = $product_view_image;
        return $this;
    }

    /**
     *====================================================
     * 首页图片
     * @param $product_index_image
     * @return $this
     * @author shuang.li
     *====================================================
     */
    public function addProductIndexImage($product_index_image){
        $this->product_index_image = $product_index_image;
        return $this;
    }

    /**
     *====================================================
     * 添加组id
     * @param $group_id
     * @return $this
     * @author shuang.li
     *====================================================
     */
    public function addGroupId($group_id){
        $this->group_id = $group_id;
        return $this;
    }

    /**
     *====================================================
     * 添加组名称
     * @param $group_name
     * @return $this
     * @author shuang.li
     *====================================================
     */
    public function addGroupName($group_name){
        $this->group_name = $group_name;
        return $this;
    }


    /**
     *====================================================
     * 创建楼层组商品
     * @return Products
     * @author shuang.li
     *====================================================
     */
    public function buildFloorGroupProduct(){
        return new Products($this);
    }

    /**
     *====================================================
     * 创建楼层组
     * @return Group
     * @author shuang.li
     *====================================================
     */
    public function buildFloorGroup(){
        return new Group($this);
    }

    /**
     *====================================================
     * 创建楼层
     * @return Floor
     * @author shuang.li
     *====================================================
     */
    public function buildFloor(){
        return new Floor($this);

    }
}

