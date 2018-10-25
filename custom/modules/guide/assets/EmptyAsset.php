<?php
namespace custom\modules\guide\assets;

use common\assets\BaseAssetBundle;

class EmptyAsset extends BaseAssetBundle{

    public $sourcePath = '@guide/views/assets';
    public $css = [];
    public $js = [];
    public $_css = [
        'css/register.css',
        'css/shopping.css',
        'css/customization.css',
    ];
    public $_js = [
        'js/register.js',
        'js/shopping.js',
        'js/customization.js',
    ];

    public $depends = [
        'custom\assets\HeaderFooterSearchAsset',
    ];
}
