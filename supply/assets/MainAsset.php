<?php
namespace supply\assets;

use common\assets\BasicAssetBundle;

class MainAsset extends BasicAssetBundle{

    public $sourcePath = '@supply/views/assets';
    public $css = [
        'css/main.css'
    ];
    public $js = [
        'js/main.js'
    ];

    public $_css = [
        'css/release/category.css',
        'css/release/product.css',
        'css/price.css',
        'css/product.css',
        'css/order.css',
        'css/refund_index.css',
        'css/refund_detail.css',
        'css/order-detail.css',
        'css/custom.css',
        'css/customDetail.css',
        'css/gpubs.css',
        'css/gpubs-detail.css'
    ];

    public $_js = [
        'js/release/category.js',
        'js/release/product.js',
        'js/price.js',
        'js/product.js',
        'js/order.js',
        'js/refund_index.js',
        'js/refund_detail.js',
        'js/order-detail.js',
        'js/custom.js',
        'js/customDetail.js',
        'js/gpubs.js',
        'js/gpubs-detail.js'
    ];

    public $depends = [
        'supply\assets\GlobalAsset',
    ];
}
