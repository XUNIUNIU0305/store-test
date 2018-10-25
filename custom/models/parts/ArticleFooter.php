<?php
namespace custom\models\parts;
use common\ActiveRecord\CustomerArticleFooterAR;
use yii\base\Object;
use Yii;

class ArticleFooter extends Object
{
    public $userId;
    private $AR;

    function init(){
        $this->AR = CustomerArticleFooterAR::findOne(['customer_user_id'=>$this->userId]);
      }

    public function setContentFooter($footerContent){
        if (!$this->AR) return false;
        $this->AR->footer_content = $footerContent;
        return $this->AR->update();
    }

    public function getContentFooter(){
        if ($this->AR) return $this->AR->footer_content;
    }

    public function createFooter($footerContent,$return='false'){
        if ($this->AR){
            return $this->setContentFooter($footerContent);
        }else{
            return Yii::$app->RQ->AR(new CustomerArticleFooterAR())->insert(['customer_user_id'=>$this->userId,'footer_content'=>$footerContent],$return);
        }
    }

}