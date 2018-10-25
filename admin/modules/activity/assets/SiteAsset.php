<?php
namespace admin\modules\activity\assets;

use common\assets\BaseAssetBundle;

class SiteAsset extends BaseAssetBundle{

    public $sourcePath = '@activity/views/assets';

    public $css = [];

    public $js = [];

    protected $_css = [
        'css/coupon-create.css',
        'css/coupon-list.css',
        'css/coupon-detail.css',
        'css/groupbuy-index.css',
        'css/index-gpubs.css',
        'css/create-gpubs.css',
        'css/detail-gpubs.css',
        'css/list-gpubs.css',
        'css/modify-gpubs.css',
        'css/gpubs-management.css',
    ];

    protected $_js = [
        'js/coupon-create.js',
        'js/coupon-detail.js',
        'js/coupon-list.js',
        'js/groupbuy-index.js',
        'js/index-gpubs.js',
        'js/create-gpubs.js',
        'js/detail-gpubs.js',
        'js/list-gpubs.js',
        'js/datepicker.js',
        'js/modify-gpubs.js',
        'js/gpubs-management.js',
    ];

    public $depends = [
        'admin\assets\GlobalAsset',
    ];
}
