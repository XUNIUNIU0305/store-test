<?php
namespace admin\modules\nanjing\assets;

use common\assets\BaseAssetBundle;

class NanjingAsset extends BaseAssetBundle{

    public $sourcePath = '@nanjing/views/assets';

    public $css = [
        'css/nanjing.css',
    ];

    public $js = [
        'js/nanjing.js',
    ];

    protected $_css = [
        'css/draw-review-detail.css',
        'css/draw-review-list.css',
        'css/fund-management.css',
    ];

    protected $_js = [
        'js/draw-review-detail.js',
        'js/draw-review-list.js',
        'js/fund-management.js',
    ];

    public $depends = [
        'admin\assets\GlobalAsset',
    ];
}
