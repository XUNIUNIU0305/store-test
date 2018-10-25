<?php
namespace custom\assets;

use common\assets\BasicAssetBundle;

class HeaderFooterOrderAsset extends BasicAssetBundle{

    public $sourcePath = '@custom/views/assets';
    public $css = [
        'css/header_footer_order.css',
    ];
    public $js = [
        'js/header_footer_order.js',
    ];
    public $_css = [
        'css/confirm-order.css',
        'css/return_success.css',
    ];
    public $_js = [
        'js/confirm-order.js',
        'js/return_success.js',
    ];

    public $depends = [
        'custom\assets\HeaderFooterWithoutindexAsset',
    ];
}
