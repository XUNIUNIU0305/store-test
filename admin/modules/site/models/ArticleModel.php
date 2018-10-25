<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/5/4
 * Time: 上午10:03
 */

namespace admin\modules\site\models;


use admin\components\handler\ArticleHandler;
use common\ActiveRecord\AdminArticleAR;
use common\models\Model;
use common\models\parts\article\Article;
use common\models\parts\OSSImage;

class ArticleModel extends Model
{

    const SCE_LIST = 'get_list';
    const SCE_CONTENT = 'get_content';
    const SCE_INSERT = 'insert';
    const SCE_EDIT = 'edit';
    const SCE_REMOVE = 'remove';

    public $current_page;
    public $page_size;

    public $id;
    public $title;
    public $content;
    public $file_name;



    public function scenarios()
    {
        return [
            self::SCE_LIST=>['current_page','page_size'],
            self::SCE_CONTENT=>['id'],
            self::SCE_INSERT=>['title','content','file_name'],
            self::SCE_EDIT=>['id','title','content','file_name'],
            self::SCE_REMOVE=>['id'],
        ];
    }


    public function rules()
    {
        return [
            [['id','title','content','file_name','current_page','page_size'],'required','message'=>9001],
            [['title'], 'string', 'length' => [1, 20],'message'=>5230],
            [['current_page', 'page_size', 'id'], 'number', 'integerOnly' => true, 'message' => 9001,],
            [['current_page'], 'default', 'value' => 1,],
            [['page_size'], 'default', 'value' => 10,],
        ];
    }

    /**
     *====================================================
     * 获取文章列表
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function getList(){
        $article = ArticleHandler::getArticleList($this->current_page,$this->page_size,['is_del'=>AdminArticleAR::NOT_DEL]);
        return [
            'count' => $article->count,
            'total_count' => $article->totalCount,
            'codes' => $article->models,
        ];
    }

    /**
     *====================================================
     * 获取对应文章内容
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function getContent(){
        $article = new Article(['id'=>$this->id]);
        return ['data'=>$article->getContent()];
    }

    /**
     *====================================================
     * 新建文章
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function insert(){
        if (ArticleHandler::create(['title'=>$this->title,'content'=>$this->content,'file_name'=>$this->file_name,'path'=>current(self::getImages()->getPath())]) !==false){
            return true;
        }
        $this->addError('create',5231);
        return false;


    }

    /**
     *====================================================
     * 编辑文章
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function edit(){
        $article = new Article(['id'=>$this->id]);
        if ($article->setArticle(['title'=>$this->title,'content'=>$this->content,'file_name'=>$this->file_name,'path'=>current(self::getImages()->getPath())]) !==false){
            return true;
        }
        $this->addError('edit',5232);
        return false;

    }

    /**
     *====================================================
     * 删除文章
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function remove()
    {
        $article = new Article(['id' => $this->id]);
        if ($article->setIsDel(AdminArticleAR::IS_DEL) != false){
            return true;
        }
        $this->addError('remove', 5233);
        return false;

    }

    public function getImages(){
       return  new OSSImage(['images' => ['filename' => $this->file_name]]);
    }



}