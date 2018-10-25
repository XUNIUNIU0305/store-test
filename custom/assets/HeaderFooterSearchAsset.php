<?php
namespace custom\assets;

use common\assets\BasicAssetBundle;

class HeaderFooterSearchAsset extends BasicAssetBundle{

    public $sourcePath = '@custom/views/assets';
    public $css = [
        'css/header_footer_search.css',
    ];
    public $js = [
        'js/header_footer_search.js',
    ];
    public $_css = [
        'css/index.css',
        'css/product.css',
        'css/cart.css',
        'css/error.css',
        'css/brand.css',
        'css/shop.css',
        'css/search-index.css',
        'css/search-category.css',
    ];
    public $_js = [
        'js/index.js',
        'js/product.js',
        'js/cart.js',
        'js/error.js',
        'js/brand.js',
        'js/shop.js',
        'js/search-index.js',
        'js/search-category.js',
        'js/requestAnimationFrame.js',
        'js/jquery.fly.min.js',
        'js/qrcode.js'
    ];

    public $depends = [
        'custom\assets\HeaderFooterWithoutindexAsset',
    ];
}
