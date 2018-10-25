<?php
namespace custom\modules\quality\assets;

use common\assets\BaseAssetBundle;

class QualitySearchAsset extends BaseAssetBundle
{
    public $sourcePath = '@quality/views/assets';

    public $css = [
    ];
    public $js = [
    ];
    public $_css = [
        'css/quality_global.css',
        'css/auth.css',
        'css/owner_list.css',
        'css/owner_detail.css',
        'css/custom_search.css',
        'css/custom_detail.css',
    ];
    public $_js = [
        'js/quality_global.js',
        'js/auth.js',
        'js/owner_list.js',
        'js/owner_detail.js',
        'js/custom_search.js',
        'js/custom_detail.js',
    ];

    public $depends = [
        'custom\modules\quality\assets\HeaderFooterSearchAsset',
    ];
}