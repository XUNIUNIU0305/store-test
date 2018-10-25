<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/5/4
 * Time: 上午11:22
 */

namespace admin\components\handler;
use common\ActiveRecord\AdminArticleAR;
use common\models\parts\article\Article;
use Yii;


use common\components\handler\Handler;
use yii\data\ActiveDataProvider;

class ArticleHandler extends Handler
{
    /**
     *====================================================
     * 获取文章列表
     * @param $currentPage
     * @param $pageSize
     * @return ActiveDataProvider
     * @author shuang.li
     *====================================================
     */
    public static function getArticleList($currentPage, $pageSize,$where)
    {
        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 1;
        return  new ActiveDataProvider([
            'query' => AdminArticleAR::find()->select(['id', 'title', 'file_name', 'path'])->where($where)->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

    }

    /**
     *====================================================
     * 新增文章
     * @param        $data
     * @param string $return
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public static  function create($data,$return = 'false'){
        return Yii::$app->RQ->AR(new AdminArticleAR())->insert($data,$return);
    }


    /**
     *====================================================
     * 删除文章
     * @param Article $article
     * @param string  $return
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public static  function delete(Article $article, $return = 'false')
    {
        return Yii::$app->RQ->AR(AdminArticleAR::findOne(['id' => $article->id]))->delete($return);
    }
}