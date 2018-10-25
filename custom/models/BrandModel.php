<?php

namespace custom\models;


use common\ActiveRecord\BrandAdvAR;
use common\ActiveRecord\BrandHomeAR;
use common\components\handler\ShopBrandHandler;
use common\models\Model;

class BrandModel extends Model
{
    const SCE_TOP_ADV = 'get_top_adv';
    const SCE_BIG_SMALL_ADV = 'get_big_small_adv';
    const SCE_HOT_BRAND = 'get_hot_brand';
    const SCE_BRAND_ALBUM = 'get_brand_album';

    public $current_page;
    public $page_size;


    public function rules()
    {
        return [
            [['current_page', 'page_size',], 'required', 'message'=>9001, 'on'=>[self::SCE_HOT_BRAND]],
        ];

    }

    public function scenarios()
    {
        return [
            self::SCE_TOP_ADV => [],
            self::SCE_BIG_SMALL_ADV => [],
            self::SCE_HOT_BRAND => ['current_page','page_size'],
            self::SCE_BRAND_ALBUM => [],
        ];
    }


    /**
     *====================================================
     * 获取主广告列表
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function getTopAdv()
    {
        return ShopBrandHandler::brandAdvList(BrandAdvAR::POSITION_BIG);
    }

    /**
     *====================================================
     * 获取小图和长图
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function getBigSmallAdv()
    {
        return ShopBrandHandler::brandAdvList([BrandAdvAR::POSITION_SMALL,BrandAdvAR::POSITION_LONG]);
    }

    /**
     *====================================================
     * 获取热销品牌列表
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function getHotBrand()
    {
        $hotBrandList =  ShopBrandHandler::hotBrandList($this->current_page,$this->page_size,BrandHomeAR::STATUS_AVAILABLE);
        return [
            'count' => $hotBrandList->count,
            'total_count' => $hotBrandList->totalCount,
            'codes' => $hotBrandList->models,
        ];
    }

    /**
     *====================================================
     * 获取品牌特辑列表
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function getBrandAlbum()
    {
        return ShopBrandHandler::brandAlbumList();
    }


}