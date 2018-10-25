<?php
namespace business\modules\quality\assets;

use common\assets\BaseAssetBundle;

class QualityAsset extends BaseAssetBundle{

    public $sourcePath = '@quality/views/assets';

    public $css = [

    ];

    public $js = [

    ];

    protected $_css = [
        'css/quality.css',
        'css/technican.css',
    ];

    protected $_js = [
        'js/index.js',
        'js/detail.js',
        'js/create.js',
        'js/date.js',
        'js/technican.js',

    ];

    public $depends = [
        'business\assets\ModuleAsset',
    ];
}
