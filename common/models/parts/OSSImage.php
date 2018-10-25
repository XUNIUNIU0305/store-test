<?php
namespace common\models\parts;

use Yii;
use common\models\parts\basic\ImageInterface;
use common\ActiveRecord\OSSUploadFileAR;
use common\ActiveRecord\FileMimetypeAR;
use common\models\RapidQuery;
use yii\base\Object;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

class OSSImage extends Object implements ImageInterface{

    /**
     * OSS图片，数据库可查询对象
     * $images可设置类型：
     * integer: 数据库主键
     * array: \yii\db\ActiveQuery可查询语句 e.g. ['filename' => 'filename.jpg']
     */
    public $images;

    //当$images未指定对象时的查询数量限制
    public $limit = 10;

    //查询排序
    public $orderBy = ['id' => SORT_ASC];

    //根据$images查询出的AcitveRecord对象
    protected $imageObj;

    protected $additionalAction = '';

    /**
     * 初始化OSSImage对象
     * 验证$images
     */
    public function init(){
        if(is_numeric($this->images)){
            if(!self::exists($this->images))throw new InvalidConfigException;
            $this->imageObj = OSSUploadFileAR::findOne($this->images);
        }else if(is_array($this->images)){
            if(!self::exists($this->images))throw new InvalidConfigException;
            $this->imageObj = OSSUploadFileAR::find()->where($this->images);
        }else{
            $this->imageObj = OSSUploadFileAR::find();
        }
    }

    /**
     * inherit
     *
     * @return integer|array
     */
    public function getId(){
        if($this->imageObj instanceof ActiveRecord){
            return $this->imageObj->id;
        }else{
            return $this->getColumnData('id');
        }
    }

    /**
     * inherit
     *
     * @return string|array
     */
    public function getName(){
        if($this->imageObj instanceof ActiveRecord){
            return $this->imageObj->filename . $this->additionalAction;
        }else{
            return array_map(function($name){
                return $name . $this->additionalAction;
            }, $this->getColumnData('filename'));
        }
    }

    public function setAdditionalAction(string $action){
        if(empty($action))return false;
        $this->additionalAction = $action;
        return true;
    }

    public function resetAdditionalAction(){
        $this->additionalAction = '';
        return true;
    }

    /**
     * 获取图片的完整路径
     *
     * @return string|array
     */
    public function getPath(){
        $filename = $this->getName();
        if(is_array($filename)){
            return array_map(function($name){
                return Yii::$app->params['OSS_PostHost'] . '/' . $name;
            }, $filename);
        }else{
            return Yii::$app->params['OSS_PostHost'] . '/' . $filename;
        }
    }

    /**
     * inherit
     *
     * @return integer|array
     */
    public function getSize(){
        if($this->imageObj instanceof ActiveRecord){
            return $this->imageObj->size;
        }else{
            return $this->getColumnData('size');
        }
    }

    /**
     * inherit
     *
     * @return string|array
     */
    public function getMimetype(){
        if($this->imageObj instanceof ActiveRecord){
            return $this->getMimetypeName($this->imageObj->file_mimetype_id);
        }else{
            return array_map(function($mimetypeId){
                return $this->getMimetypeName($mimetypeId);
            }, $this->getColumnData('file_mimetype_id'));
        }
    }

    /**
     * inherit
     *
     * @return integer|array
     */
    public function getWidth(){
        if($this->imageObj instanceof ActiveRecord){
            return $this->imageObj->width;
        }else{
            return $this->getColumnData('width');
        }
    }

    /**
     * inherit
     *
     * @return integer|array
     */
    public function getHeight(){
        if($this->imageObj instanceof ActiveRecord){
            return $this->imageObj->height;
        }else{
            return $this->getColumnData('height');
        }
    }

    /**
     * 获取上传者类型
     *
     * @return integer|array
     */
    public function getUploaderType(){
        if($this->imageObj instanceof ActiveRecord){
            return $this->imageObj->upload_user_type;
        }else{
            return $this->getColumnData('upload_user_type');
        }
    }

    /**
     * 获取上传者ID
     *
     * @return integer|array
     */
    public function getUploaderId(){
        if($this->imageObj instanceof ActiveRecord){
            return $this->imageObj->upload_user_id;
        }else{
            return $this->getColumnData('upload_user_id');
        }
    }

    /**
     * 查询$params条件的OSS图片是否存在
     *
     * @return boolean
     */
    public static function exists($params){
        return (new RapidQuery(new OSSUploadFileAR))->exists([
            'where' => is_array($params) ? $params : ['id' => $params],
        ]);
    }

    /**
     * 获取mimetype名称
     *
     * @return string
     */
    private function getMimetypeName($mimetypeId){
        return (new RapidQuery(new FileMimetypeAR))->scalar([
            'select' => ['name'],
            'where' => ['id' => $mimetypeId],
        ]);
    }

    /**
     * 获取数据库列数据
     *
     * @return array
     */
    protected function getColumnData($attr){
        return $this->imageObj->select([$attr])->orderBy($this->orderBy)->limit($this->limit)->asArray()->column();
    }
}
