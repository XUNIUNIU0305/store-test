<?php
namespace business\assets;

use common\assets\BaseAssetBundle;

class IndexAsset extends BaseAssetBundle{

    public $sourcePath = '@business/views/assets';

    public $css = [];

    public $js = [];

    public $_css = [
        'css/index.css',
        'css/register.css',
        'css/error.css',
        'css/password.css',
    ];

    public $_js = [
        'js/index.js',
        'js/register.js',
        'js/error.js',
        'js/password.js',
    ];

    public $depends = [
        'business\assets\GlobalAsset',
    ];
}
