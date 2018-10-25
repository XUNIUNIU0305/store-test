<?php
namespace custom\assets;

use common\assets\BasicAssetBundle;

class EmptyAsset extends BasicAssetBundle{

    public $sourcePath = '@custom/views/assets';

    public $_css = [
        'css/login.css',
        'css/forget.css',
        'css/register.css',
        'css/quality-index.css'
    ];
    public $_js = [
        'js/login.js',
        'js/register.js',
        'js/forget.js',
        'js/quality-index.js'
    ];

    public $depends = [
        'custom\assets\GlobalAsset',
    ];
}
