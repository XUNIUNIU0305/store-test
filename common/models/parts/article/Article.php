<?php
namespace common\models\parts\article;
use common\ActiveRecord\AdminArticleAR;
use yii\base\InvalidCallException;
use yii\base\Object;
use Yii;

class Article extends Object
{
    public $id;
    private $AR;

    public function init(){
        if (!$this->id || !$this->AR = AdminArticleAR::findOne($this->id)) throw new InvalidCallException();
    }

    public function setArticle($data){
        return Yii::$app->RQ->AR($this->AR)->update($data);
    }

    public function setIsDel($isDel){
        $this->AR->is_del = $isDel;
        return $this->AR->update();
    }

    public function getContent(){
        return $this->AR->content;
    }

    public function getTitle(){
        return $this->AR->title;
    }

    public function getPath(){
        return $this->AR->path;
    }





}