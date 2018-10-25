<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/9
 * Time: 下午6:58
 */

namespace admin\modules\site\models;

use admin\components\handler\FloorHandler;
use admin\modules\site\models\floor\FloorBuilder;
use common\ActiveRecord\AdminFloorAR;
use common\ActiveRecord\AdminFloorGoodsAR;
use common\ActiveRecord\AdminFloorGroupAR;
use common\ActiveRecord\ProductAR;
use common\components\handler\Handler;
use common\models\Model;
use common\models\parts\OSSImage;
use common\models\parts\Product;
use common\models\parts\supply\SupplyUser;
use admin\modules\site\models\floor\Floor;
use admin\modules\site\models\floor\Group;
use admin\modules\activity\models\groupbuy\GroupbuyModel;
use Yii;

class FloorModel extends Model
{
    const FL_EDIT_FLOOR = 'edit_floor';
    const FL_EDIT_GROUP = 'edit_group';
    const FL_EDIT_PRODUCT = 'edit_product';
    const FL_DELETE_FLOOR = 'delete_floor';
    const FL_DELETE_GROUP = 'delete_group';
    const FL_SEARCH_PRODUCT = 'get_product_list';
    const FL_PRODUCT_INFO = 'get_product_info';
    const FL_FLOOR_INFO = 'get_floor_info';
    const FL_FLOOR_DETAIL = 'get_floor_detail';
    const FL_FLOOR_LIST = 'get_floor_list';

    const FL_DELETE_PRODUCT = 'delete_product';
    const FL_FLOOR_STATUS = 'set_floor_status';
    const FL_PRODUCT_SORT = 'set_product_sort';

    const MAX_FLOOR_COUNT = 50;

    //楼层id ,名称，url，颜色
    public $floor_id,$name,$url,$color,$type,$status;

    //组id，楼层组名
    public $group_id,$group_name;

    //商品id，标题，卖点,展示图片，首页图片
    public $product_id,$product_original_id,$title,$sell_point,$view_image,$index_image;

    //搜索商品名称
    public $product_name;

    //商品拖拽排序
    public $product_sort;


    public function scenarios()
    {
        return [
            self::FL_FLOOR_LIST => [],
            self::FL_DELETE_FLOOR => ['floor_id'],
            self::FL_SEARCH_PRODUCT => ['product_name'],
            self::FL_PRODUCT_INFO => ['product_id'],
            self::FL_FLOOR_DETAIL => ['product_id'],
            self::FL_EDIT_FLOOR => ['floor_id','name','url','color','type'],
            self::FL_EDIT_GROUP => ['floor_id','group_id','group_name'],
            self::FL_EDIT_PRODUCT => ['group_id','product_id','product_original_id','title','sell_point','view_image','index_image'],
            self::FL_DELETE_PRODUCT => ['product_id'],
            self::FL_FLOOR_INFO => ['floor_id'],
            self::FL_FLOOR_STATUS => ['floor_id','status'],
            self::FL_DELETE_GROUP => ['group_id'],
            self::FL_PRODUCT_SORT => ['product_sort'],
        ];
    }

    public function rules()
    {
        return [
            [
                ['product_name', 'product_sort'],
                'required',
                'message'=>9001,
            ],
            [
                ['floor_id', 'product_id'],
                'integer',
                'message' => 5097,
            ],
            [
                ['floor_id'],
                'exist',
                'targetClass'=>AdminFloorAR::className(),
                'targetAttribute'=>['floor_id'=>'id'],
                'message'=>5388,
            ],
//            [
//                ['product_id'],
//                'exist',
//                'targetClass'=>AdminFloorGoodsAR::className(),
//                'targetAttribute'=>['product_id'=>'id'],
//                'message'=>5387,
//            ],
            [
                ['product_original_id'],
                'exist',
                'targetClass'=>ProductAR::className(),
                'targetAttribute'=>['product_original_id'=>'id'],
                'message'=>5387,
            ],
            [
                ['group_id'],
                'exist',
                'targetClass'=>AdminFloorGroupAR::className(),
                'targetAttribute'=>['group_id'=>'id'],
                'message'=>5387,
            ],
            [
                ['url'],
                'url',
                'message'=>5385
            ],
        ];
    }

    /**
     *====================================================
     * 楼层
     * @return array|bool
     * @author shuang.li
     *====================================================
     */
    public function editFloor()
    {
        try{
            $floor = (new FloorBuilder([
                'id' => $this->floor_id,
                'name' => $this->name,
                'url' => $this->url,
                'color' => $this->color,
                'type' => $this->type,
            ]))->buildFloor();

            return [
                'floor_id' => $floor->id,
            ];
        }catch (\Exception $exception){
            if($exception->getCode() === Floor::HAS_COUNT_ERROR) {
                $this->addError('editFloor', 5389);
                return false;
            }
            if($exception->getCode() === Floor::HAS_NAME_ERROR) {
                $this->addError('editFloor', 5501);
                return false;
            }
            $this->addError('editFloor', 5380);
            return false;
        }
    }

    /**
     *====================================================
     * 编辑楼层组
     * @return array|bool
     * @author shuang.li
     *====================================================
     */
    public function editGroup(){
        try{
            $floorBuilder = new FloorBuilder([
                'id' => $this->floor_id,
                'group_id' => $this->group_id,
            ]);
            $group = $floorBuilder->addGroupName($this->group_name)
                ->buildFloorGroup();

            return [
                'group_id' => $group->id,
                'group_name' => $group->name,
            ];
        }catch (\Exception $exception){
            if($exception->getCode() === Group::HAS_NAME_ERROR) {
                $this->addError('editGroup', 5510);
                return false;
            }
            $this->addError('editGroup', 5381);
            return false;
        }
    }


    /**
     *====================================================
     * 编辑商品
     * @return array|bool
     * @author shuang.li
     *====================================================
     */
    public function editProduct(){
        try {
            $floorBuilder = new FloorBuilder([
                'group_id' => $this->group_id,
                'product_id' => $this->product_id
            ]);
            $product = $floorBuilder->addOriginalProductId($this->product_original_id)
                ->addProductIndexImage($this->index_image)
                ->addProductTitle($this->title)
                ->addProductSellPoint($this->sell_point)
                ->buildFloorGroupProduct();
            return [
                'product_id' => $product->id,
            ];
        }catch (\Exception $exception){
            $this->addError('editGroup', 5381);
            return false;
        }
    }

    /**
     *====================================================
     * 新建获取商品详细信息
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function getProductInfo(){
        return Handler::getMultiAttributes(new Product(['id' => $this->product_id]), [
            'id',
            'title',
            'description',
            'price' => 'guidancePrice',
            'supplier_name' => 'supplier',
            'main_image' => 'mainImage',
            'big_images'=>'bigImages',
            '_func' => [
                'mainImage' => function ($img)
                {
                    return $img->path;
                },
                'bigImages' => function($image){
                    return $image->path;
                },
                'supplier' => function ($supplierId)
                {
                    return (new SupplyUser(['id' => $supplierId]))->getBrandName();
                }
            ]
        ]);
    }

    /**
     * 编辑获取商品详细信息
     * @return array
     */
    public function getFloorDetail()
    {
        return current(FloorHandler::getFloorProduct($this->product_id));
    }

    /**
     *====================================================
     * 获取添加楼层商品列表
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function getProductList(){
        $productId = ProductAR::find()->select(['id'])
            ->where(['sale_status' => Product::SALE_STATUS_ONSALE])->andWhere([
            'like',
            'title',
            $this->product_name
        ])->orWhere([
            'id' => $this->product_name
        ])->asArray()->column();
    
        return array_map(function ($id)
        {
            return Handler::getMultiAttributes(new Product(['id' => $id]), [
                'id',
                'title',
                'description',
                'price' => 'price',//2017年12月24日修改,原始是guidancePrice
                'supplier_name' => 'supplier',
                'main_image' => 'mainImage',
                'big_images'=>'bigImages',
                '_func' => [
                    'mainImage' => function ($img)
                    {
                        return $img->path;
                    },
                    'bigImages' => function($image){
                        return $image->path;
                    },
                    'supplier' => function ($supplierId)
                    {
                        return (new SupplyUser(['id' => $supplierId]))->getBrandName();
                    }
                ]
            ]);
        }, $productId);
    }


    /**
     *====================================================
     * 删除商品
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function deleteProduct(){
        if(AdminFloorGoodsAR::findOne($this->product_id)->delete()){
            return true;
        }
        $this->addError('deleteProduct',5382);
        return false;
    }


    /**
     *====================================================
     * 删除楼层组
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function deleteGroup(){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            //开启事物
            if(AdminFloorGroupAR::findOne($this->group_id)->delete() !==false && AdminFloorGoodsAR::deleteAll(['gid'=>$this->group_id])!==false){
                $transaction->commit();
                return true;
            }
        }catch (\Exception $exception){
            $transaction->rollBack();
            $this->addError('deleteGroup',5383);
            return false;
        }
    }


    /**
     *====================================================
     * 删除楼层
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function deleteFloor(){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $groupId = AdminFloorGroupAR::find()->select(['id'])->where(['floor_id'=>$this->floor_id])->asArray()->column();
            //开启事物
            if(AdminFloorAR::findOne($this->floor_id)->delete() !==false && AdminFloorGroupAR::deleteAll(['floor_id'=>$this->floor_id])!==false && AdminFloorGoodsAR::deleteAll(['gid'=>$groupId]) !==false){
                $transaction->commit();
                return true;
            }
        }catch (\Exception $exception){
            $transaction->rollBack();
            $this->addError('deleteFloor',5384);
            return false;
        }
    }

    /**
     *====================================================
     * 设置楼层状态
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function setFloorStatus(){
        if(AdminFloorAR::findOne($this->floor_id)->updateAttributes(['status'=>$this->status])){
            return true;
        }
        $this->addError('setFloorStatus',5386);
        return false;
    }

    /**
     *====================================================
     * 楼层信息
     * @return array|null|\yii\db\ActiveRecord
     * @author shuang.li
     *====================================================
     */
    public function getFloorInfo(){
       return  FloorHandler::getFloorInfo($this->floor_id);
    }

    /**
     *====================================================
     * 获取楼层列表
     * @return array|\yii\db\ActiveRecord[]
     * @author shuang.li
     *====================================================
     */
    public function getFloorList(){
        return FloorHandler::getFloorList();
    }


    /**
     *====================================================
     * 设置商品排序
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function setProductSort(){
        if(!is_array($this->product_sort)){
            $this->addError('product_sort', 9002);
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($this->product_sort as $sort=>$id){
                AdminFloorGoodsAR::updateAll(['sort' => ++$sort], ['id' => $id]);
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e){
            $transaction->rollBack();
            $this->addError('setProductSort',5386);
            return false;
        }
    }
}
