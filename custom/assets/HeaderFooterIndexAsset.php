<?php
namespace custom\assets;

use common\assets\BaseAssetBundle;

class HeaderFooterIndexAsset extends BaseAssetBundle{

    public $sourcePath = '@custom/views/assets';

    public $css = [];
    public $js = [];

    public $_css = [
        'css/index.css',
    ];

    public $_js = [
        'js/index.js',
    ];

    public $depends = [
        'custom\assets\HeaderFooterAsset',
    ];
}
