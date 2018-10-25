<?php
namespace business\modules\site\assets;

use common\assets\BaseAssetBundle;

class SiteAsset extends BaseAssetBundle{

    public $sourcePath = '@site/views/assets';

    public $css = [
        'css/site.css',
    ];

    public $js = [
        'js/site.js',
    ];

    protected $_css = [
        'css/custom.css',
        'css/admin.css',
        'css/area.css',
        'css/contrast.css',
        'css/promoter.css',
        'css/stream.css',
        'css/invite.css',
        'css/review.css',
    ];

    protected $_js = [
        'js/custom.js',
        'js/admin.js',
        'js/area.js',
        'js/contrast.js',
        'js/promoter.js',
        'js/invite.js',
        'js/review.js',
        'js/stream.js',

    ];

    public $depends = [
        'business\assets\ModuleAsset',
    ];
}
