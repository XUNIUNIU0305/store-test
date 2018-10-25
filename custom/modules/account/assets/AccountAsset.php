<?php
namespace custom\modules\account\assets;

use common\assets\BaseAssetBundle;

class AccountAsset extends BaseAssetBundle{

    public $sourcePath = '@account/views/assets';
    public $css = [
        'css/account.css',
    ];
    public $js = [
        'js/account.js',
    ];
    public $_css = [
        'css/index.css',
        'css/address.css',
        'css/order.css',
        'css/youga.css',
        'css/mobile.css',
        'css/profile.css',
        'css/refund_index.css',
        'css/refund_detail.css',
        'css/refund_create.css',
        'css/statement.css',
        'css/wechat.css',
        'css/article_index.css',
        'css/article_detail.css',
        'css/coupon-index.css',
        'css/coupon-active.css',
        'css/auth-index.css',
        'css/membrane.css',
        'css/recharge.css',
        'css/password.css',
        'css/gpubs-order-list.css',
        'css/gpubs-picking-up.css',
    ];
    public $_js = [
        'js/index.js',
        'js/address.js',
        'js/order.js',
        'js/youga.js',
        'js/mobile.js',
        'js/profile.js',
        'js/refund_index.js',
        'js/refund_detail.js',
        'js/refund_create.js',
        'js/statement.js',
        'js/wechat.js',
        'js/coupon-index.js',
        'js/coupon-active.js',
        'js/article_index.js',
        'js/article_detail.js',
        'js/auth-index.js',
        'js/membrane.js',
        'js/recharge.js',
        'js/password.js',
        'js/gpubs-order-list.js',
        'js/gpubs-picking-up.js',
    ];

    public $depends = [
        'custom\assets\HeaderFooterSearchAsset',
    ];
}
