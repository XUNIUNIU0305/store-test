<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/17
 * Time: 14:15
 */

namespace mobile\assets;


use common\assets\BaseAssetBundle;

class BaseAsset extends BaseAssetBundle
{
    public $sourcePath = '@mobile/views/assets';
    public $css = [
        'css/base.css',
    ];
    public $js = [
        'js/base.js',
    ];

    public $_css = [
        'css/app.css',
        'css/shopping-coupon.css',
        'css/confirmOrder.css',
        'css/search-category.css',
        'css/search-index.css',
        'css/search-detail.css',
        'css/shop-index.css',
    ];

    public $_js = [
        'js/index.js',
        'js/swipeSlide.min.js',
        'js/confirmOrder.js',
        'js/search-category.js',
        'js/search-index.js',
        'js/search-detail.js',
        'js/shop-index.js',
    ];
    public $depends = [
        'mobile\assets\GlobalAsset',
    ];

}