<?php
namespace custom\modules\quality\assets;

use common\assets\BasicAssetBundle;

class HeaderFooterSearchAsset extends BasicAssetBundle
{
    public $sourcePath = '@quality/views/assets';

    public $css = [
    ];
    public $js = [
    ];
    public $_css = [
        // 'css/test.css',
    ];
    public $_js = [
        // 'js/test.js',
    ];

    public $depends = [
        'custom\modules\quality\assets\HeaderFooterWithoutindexAsset',
    ];
}