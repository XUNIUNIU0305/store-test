<?php
namespace custom\assets;

use common\assets\BaseAssetBundle;

class HeaderFooterWithoutindexAsset extends BaseAssetBundle{

    public $sourcePath = '@custom/views/assets';

    public $css = [
        'css/header_footer_withoutindex.css',
    ];

    public $js = [
        'js/header_footer_withoutindex.js',
    ];

    public $_js = [];

    public $_css = [];

    public $depends = [
        'custom\assets\HeaderFooterAsset',
    ];
}
