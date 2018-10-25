<?php
namespace supply\assets;

use common\assets\BasicAssetBundle;

class IndexAsset extends BasicAssetBundle{

    public $sourcePath = '@supply/views/assets';
    public $css = [
        'css/index.css',
    ];
    public $js = [
        'js/index.js',
    ];

    public $_css = [
        'css/error.css',
    ];

    public $_js = [
        'js/error.js',
    ];

    public $depends = [
        'supply\assets\GlobalAsset',
    ];
}
