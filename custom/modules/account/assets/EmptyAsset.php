<?php
namespace custom\modules\account\assets;

use common\assets\BaseAssetBundle;

class EmptyAsset extends BaseAssetBundle{

    public $sourcePath = '@account/views/assets';
    public $css = [];
    public $js = [];
    public $_css = [
        'css/order_detail.css',
        'css/refund_detail.css',
        'css/gpubs-order-detail.css'
    ];
    public $_js = [
        'js/order_detail.js',
        'js/refund_detail.js',
        'js/gpubs-order-detail.js'
    ];

    public $depends = [
        'custom\assets\HeaderFooterSearchAsset',
    ];
}
