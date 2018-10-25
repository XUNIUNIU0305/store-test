<?php
namespace admin\modules\site\models\floor;

use common\ActiveRecord\AdminFloorGoodsAR;
use yii\base\Object;
use Yii;

//楼层商品
class Products extends Object {
    public $id;
    protected $original_id;//原商品id
    protected $view_image;//原图
    protected $index_image;//首页图片
    protected $title;//标题
    protected $sell_point;//卖点
    protected $gid;

    public function __construct(FloorBuilder $builder)
    {
        parent::__construct();
        $this->id = $builder->product_id;
        $this->original_id= $builder->original_product_id;
        $this->view_image = $builder->product_view_image;
        $this->index_image = $builder->product_index_image;
        $this->title = $builder->product_title;
        $this->sell_point = $builder->product_sell_point;
        $this->gid = $builder->group_id;

        if ($this->id){
            if(AdminFloorGoodsAR::findOne($this->id)->updateAttributes([
                'original_id'=>$this->original_id,
                'view_image'=>$this->view_image,
                'index_image'=>$this->index_image,
                'title'=>$this->title,
                'sell_point'=>$this->sell_point,
            ]) ===false){
                throw  new \Exception();
            }
        }elseif($this->original_id){
            if (!$id = Yii::$app->RQ->AR(new AdminFloorGoodsAR())->insert([
                'original_id' => $this->original_id,
                'view_image' => $this->view_image,
                'index_image' => $this->index_image,
                'title' => $this->title,
                'sell_point' => $this->sell_point,
                'gid' => $this->gid,
                    ])) {
                throw new \Exception();
            }
        }else{

            $this->id = $id;
            if (!AdminFloorGoodsAR::findOne($this->id)->updateAttributes([
                'sort'=>$this->id,
            ])) {
                throw new \Exception();
            }
        }
    }
}
