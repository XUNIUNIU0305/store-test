<?php
namespace common\models\parts\brand;
use common\ActiveRecord\BrandAdvAR;
use common\ActiveRecord\BrandShopAdvAR;
use common\models\parts\OSSImage;
use common\models\parts\supply\SupplyUser;
use yii\base\InvalidCallException;
use yii\base\Object;
use Yii;


class ShopAdv extends Object
{
    public $id;
    private $AR;

    const TYPE_BIG_IMG=0;//主图大图
    const TYPE_SMALL_IMG=1;//热销小图
    const TYPE_SUB_IMG=2;//子图
    const TYPE_WAP_BIG_IMG = 3; //wap版主图
    const TYPE_WAP_CAROUSEL_IMG = 4; //wap版轮播图

    //每个广告位所需图片数量
    const TYPE_BIG_IMG_LENGTH=1;
    const TYPE_SMALL_IMG_LENGTH=2;
    const TYPE_SUB_IMG_LENGTH=3;


    //广告位图片排序取值
    const SORT_TOP_ADV=0;//
    const SORT_HOT_FIRST_ADV=1;
    const SORT_HOT_SECOND_ADV=2;
    const SORT_SUB_FIRST_ADV=3;
    const SORT_SUB_SECOND_ADV=4;
    const SORT_SUB_THIRD_ADV=5;

    function init(){
        if(!$this->id || !($this->AR = BrandShopAdvAR::findOne($this->id))) throw new InvalidCallException();
    }


    //获取文件名称
    public function getFileName(){
        return $this->AR->file_name;
    }

    //获取图片路径
    public function getImagePath(){
        return $this->AR->image_path;
    }
    //获取图片URL
    public function getImageUrl(){
        return $this->AR->image_url;
    }

    public function getType(){
        return $this->AR->type;
    }

    //获取所属商户
    public function getSupplier(){
        return new SupplyUser(['id'=>$this->AR->supply_user_id]);
    }


    public function setMulti($fileName,$imagePath,$imageUrl,$return = 'false'){
        return Yii::$app->RQ->AR($this->AR)->update([
            'file_name'=>$fileName,
            'image_path'=>$imagePath,
            'image_url'=>$imageUrl,
        ], $return);
    }

}