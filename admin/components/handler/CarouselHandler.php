<?php
/**
 * Created by PhpStorm.
 * User: lishuang
 * Date: 2017/3/6
 * Time: 下午6:21
 */

namespace admin\components\handler;

use common\ActiveRecord\AdminImageCarouselAR;
use common\components\handler\Handler;
use yii\data\ActiveDataProvider;


class CarouselHandler extends Handler
{
    /**
     *====================================================
     * 轮播列表数据
     * @param     $currentPage
     * @param     $pageSize
     * @param int $isDel
     * @return ActiveDataProvider
     * @author shuang.li
     * @Date:
     *====================================================
     */
    public static function provideCarousel($currentPage, $pageSize, $isDel = 0)
    {
        $currentPage = (int)$currentPage or $currentPage = 1;
        $pageSize = (int)$pageSize or $pageSize = 1;
        return new ActiveDataProvider([
            'query' => AdminImageCarouselAR::find()->select([
                'id',
                'file_name',
                'img_url',
                'product_url',
                'sort',
                'is_del',
            ])->filterWhere(['is_del' => $isDel])->asArray(),
            'pagination' => [
                'page' => $currentPage - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC,
                ],
            ],
        ]);
    }
}