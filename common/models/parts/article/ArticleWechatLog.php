<?php
namespace common\models\parts\article;
use common\ActiveRecord\ArticleWechatLogAR;
use custom\models\parts\ArticleFooter;
use yii\base\Component;

use Yii;
class ArticleWechatLog extends Component
{
    public $userId;
    public $articleId;
    private $AR;

    public function init(){
        $this->AR = ArticleWechatLogAR::findOne(['user_id'=>$this->userId,'article_id'=>$this->articleId]);
    }

    public function shareLog(){
        if ($this->AR){
            $this->AR->updateCounters(['num' => 1]);
        }else{
            Yii::$app->RQ->AR(new ArticleWechatLogAR())->insert(['user_id'=>$this->userId, 'article_id'=>$this->articleId]);
        }
    }


}