<?php
namespace common\models\parts;

use Yii;
use common\models\RapidQuery;
use yii\base\InvalidParamException;
use common\ActiveRecord\ProductBigImagesAR;
use common\ActiveRecord\ProductAR;

class ProductImage extends OSSImage{

    //商品id
    public $productId;

    public function init(){
        $this->setImages();
        parent::init();
    }

    /**
     * 获取图片排序
     *
     * @return array
     */
    public function getSorted(){
        $images = (new RapidQuery(new ProductBigImages))->all([
            'select' => ['oss_upload_file_id', 'sort'],
            'where' => [
                'product_id' => $this->productId,
            ],
            'orderBy' => ['sort' => SORT_ASC],
        ]);
        return array_column($images, 'sort', 'oss_upload_file_id');
    }

    /**
     * 获取商品主图
     *
     * @return Object OSSImage
     */
    public function getMainImage(){
        if(is_null($this->productId)){
            $bigImagesId = (new RapidQuery(new ProductAR))->column([
                'select' => ['product_big_images_id'],
                'orderBy' => $this->orderBy,
                'limit' => $this->limit,
            ]);
        }else{
            $bigImagesId = (new RapidQuery(new ProductAR))->scalar([
                'select' => ['product_big_images_id'],
                'where' => ['id' => $this->productId],
            ]);
        }
        $queryMethod = is_array($bigImagesId) ? 'column' : 'scalar';
        $ossUploadFileId = (new RapidQuery(new ProductBigImagesAR))->$queryMethod([
            'select' => ['oss_upload_file_id'],
            'where' => ['id' => $bigImagesId],
        ]);
        return new OSSImage(['images' => is_array($ossUploadFileId) ? ['id' => $ossUploadFileId] : $ossUploadFileId]);
    }

    /**
     * 图片是否存在
     *
     * @return boolean
     */
    public function has($params = null){
        if(is_null($params)){
            return !is_null($this->images);
        }else{
            return parent::exists($params);
        }
    }

    /**
     * 设置图片
     */
    protected function setImages(){
        $imagesId = (new RapidQuery(new ProductBigImagesAR))->column([
            'select' => ['oss_upload_file_id'],
            'filterWhere' => ['product_id' => $this->productId],
            'orderBy' => $this->orderBy,
            'limit' => $this->limit,
        ]);
        $this->images = empty($imagesId) ? null : (count($imagesId) == 1 ? current($imagesId) : ['id' => $imagesId]);
    }
}
