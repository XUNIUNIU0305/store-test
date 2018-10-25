<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/5/4
 * Time: 下午3:34
 */

namespace custom\modules\account\models;


use common\ActiveRecord\AdminArticleAR;
use common\ActiveRecord\CustomerArticleFooterAR;
use common\ActiveRecord\CustomUserAR;
use common\models\Model;
use common\models\parts\article\Article;
use common\models\parts\article\ArticleWechatLog;
use custom\components\handler\ArticleHandler;
use custom\models\parts\ArticleFooter;
use Yii;
use dosamigos\qrcode\QrCode;
use yii\web\Response;

class ArticleModel extends  Model
{
    const SCE_LIST = 'get_list';
    const SCE_EDIT = 'edit';
    const SCE_CREATE = 'create';
    const SCE_DETAIL = 'detail';
    const SCE_OPEN_DETAIL = 'open_detail';
    const SCE_QR_CODE = 'get_qr_code';
    const SCE_LOG = 'log';


    public $id;
    public $current_page;
    public $page_size;
    public $footer_content;

    public $footer_id;

    public function rules()
    {
         return [
             [['current_page', 'page_size'],'required','message'=>9001],
             [['current_page', 'page_size', 'id','footer_id'], 'number', 'integerOnly' => true, 'message' => 9001],
             [['current_page'], 'default', 'value' => 1,],
             [['page_size'], 'default', 'value' => 10,],
             [['footer_id'],'exist','targetClass'=>CustomUserAR::className(),'targetAttribute'=>'id','message'=>9001,'on'=>[self::SCE_LOG]],
             [['id'],'exist','targetClass'=>AdminArticleAR::className(),'targetAttribute'=>'id','filter'=>['is_del'=>AdminArticleAR::NOT_DEL],'message'=>5240,'on'=>[self::SCE_OPEN_DETAIL,self::SCE_DETAIL]],
             [['customer_user_id'],'exist','targetClass'=>CustomerArticleFooterAR::className(),'targetAttribute'=>'id','message'=>9001,'on'=>[self::SCE_OPEN_DETAIL]],
         ];
    }

    public function scenarios()
    {
        return [
            self::SCE_LIST => ['current_page', 'page_size'],
            self::SCE_EDIT => ['footer_content'],
            self::SCE_CREATE => ['footer_content'],
            self::SCE_DETAIL => ['id'],
            self::SCE_OPEN_DETAIL => ['id','footer_id'],
            self::SCE_QR_CODE => ['id','footer_id'],
            self::SCE_LOG => ['id','footer_id'],
        ];
    }

    public function getList(){
        $article = ArticleHandler::provideArticleList($this->current_page,$this->page_size,['is_del'=>AdminArticleAR::NOT_DEL]);
        return [
            'count' => $article->count,
            'total_count' => $article->totalCount,
            'codes' => $article->models,
        ];

    }

    public function openDetail(){
        $article = new Article(['id'=>$this->id]);
        $articleFooter = new ArticleFooter(['userId'=>$this->footer_id]);
        return [
            'path'=>$article->path,
            'title'=>$article->title,
            'content'=>$article->content,
            'footer_id'=>$this->footer_id,
            'footer'=>$articleFooter->contentFooter,
        ];

    }

    public function getQrCode(){
        if(!$this->validate())return false;
        $host = Yii::$app->request->hostInfo;
        $response = Yii::$app->response;
        $response->headers->set('Content-Type', 'image/png');
        $response->format = Response::FORMAT_RAW;
        return QrCode::png($host.'/account/article/wechat?id='.$this->id.'&footer_id='.Yii::$app->user->id,false,0,10,1);

    }

    public function detail(){
        $article = new Article(['id'=>$this->id]);
        return [
            'title'=>$article->title,
            'content'=>$article->content,
            'footer_id' => Yii::$app->user->id,
            'footer'=>Yii::$app->CustomUser->article->contentFooter,
        ];

    }

    public function edit(){
       if (Yii::$app->CustomUser->article->setContentFooter($this->footer_content) !== false){
            return true;
       }
       $this->addError('edit',3290);
       return false;

    }

    public function create(){
        if (Yii::$app->CustomUser->article->createFooter($this->footer_content) !== false){
            return true;
        }
        $this->addError('create',3291);
        return false;
    }


    public function log(){
        if (empty($this->id)) return false;
        if (Yii::$app->RQ->AR(new AdminArticleAR())->exists(['where'=>['id'=>$this->id,'is_del'=>AdminArticleAR::IS_DEL], 'limit'=>1])) return false;
        $articleWechatLog = new ArticleWechatLog(['articleId'=>$this->id,'userId'=>$this->footer_id]);
        $articleWechatLog->shareLog($this->id);
    }

}