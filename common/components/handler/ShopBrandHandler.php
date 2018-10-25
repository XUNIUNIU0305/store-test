<?php
namespace common\components\handler;

use common\ActiveRecord\BrandAdvAR;
use common\ActiveRecord\BrandHomeAR;
use common\ActiveRecord\BrandShopAdvAR;
use common\models\parts\brand\BrandAdv;
use common\models\parts\brand\BrandHome;
use Yii;
use yii\data\ActiveDataProvider;


class ShopBrandHandler extends Handler
{
    /**
     *====================================================
     * 获取广告列表
     * @param $position
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public static function brandAdvList($position)
    {
        return Yii::$app->RQ->AR(new BrandAdvAR())->all([
            'select'=>['id','path','url','position','sort','file_name'],
            'where'=>[
                'position'=>$position
            ],
            'orderBy' => 'sort desc',
        ]);

    }


    /**
     *====================================================
     * 获取热销品牌列表
     * @param     $currentPage
     * @param     $pageSize
     * @param int $status
     * @param int $type
     * @return ActiveDataProvider
     * @author shuang.li
     *====================================================
     */
    public static function hotBrandList($currentPage, $pageSize, $status,$type = BrandHomeAR::TYPE_HOT_BRAND)
    {
        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 1;
        return new ActiveDataProvider([
            'query' => BrandHomeAR::find()->select([
                'id',
                'type', //默认为0 热销品牌
                'url',  //图片链接
                'file_name', //路径
                'path', //路径
                'sort', //排序
                'status',   //状态
            ])->where(['status' => $status,'type'=>$type])->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_DESC,
                ],
            ],
        ]);

    }

    /**
     *====================================================
     * 获取广告特辑列表
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public static function brandAlbumList()
    {
        return Yii::$app->RQ->AR(new BrandHomeAR())->all([
            'select'=>['id','url','sort','logo','logo_path','background','background_path','title','introduction'],
            'where'=>[
                'type'=>BrandHomeAR::TYPE_BRAND_ALBUM,
                'status'=>BrandHomeAR::STATUS_AVAILABLE,
            ],
            'orderBy' => 'sort desc',
        ]);

    }


    /**
     *====================================================
     * 创建主广告
     * @param $sort
     * @param $fileName
     * @param $path
     * @param $url
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public static function createHeaderAdv($sort,$fileName,$path,$url)
    {
        return Yii::$app->RQ->AR(new BrandAdvAR())->insert([
            'sort' => $sort,
            'file_name' => $fileName,
            'path' => $path,
            'url' => $url
        ]);
    }

    /**
     *====================================================
     * 创建热门品牌或品牌特辑
     * @param string $fileName
     * @param int    $sort
     * @param string $path
     * @param string $url
     * @param int    $type
     * @param        $logo
     * @param        $logoPath
     * @param        $background
     * @param        $backgroundPath
     * @param        $url
     * @param        $introduction
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public static function createBrand($fileName = '', $sort = 0, $path = '', $url = '', $type = 0, $logo = '', $logoPath = '', $background = '', $backgroundPath = '', $introduction = '',$title = '')
    {
        return Yii::$app->RQ->AR(new BrandHomeAR())->insert([
            'file_name' => $fileName,
            'sort' => $sort,
            'path' => $path,
            'url' => $url,
            'type'=>$type,
            'logo'=> $logo,
            'logo_path'=>$logoPath,
            'background'=>$background,
            'background_path'=>$backgroundPath,
            'introduction'=>$introduction,
            'title'=>$title,
        ]);
    }


    /**
     *====================================================
     * 删除主广告
     * @param BrandAdv $brandAdv
     * @param string   $return
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public static function deleteBrandAdv(BrandAdv $brandAdv, $return = 'false')
    {
        return Yii::$app->RQ->AR(BrandAdvAR::findOne(['id' => $brandAdv->id]))->delete($return);
    }

    /**
     *====================================================
     * 删除品牌特辑
     * @param array $id
     * @return int
     * @author shuang.li
     *====================================================
     */
    public static function deleteBrandHome(array $id)
    {
        return BrandHomeAR::deleteAll(['id'=>$id]);
    }
}

