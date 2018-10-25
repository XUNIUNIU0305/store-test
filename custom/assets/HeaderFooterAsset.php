<?php
namespace custom\assets;

use common\assets\BasicAssetBundle;

class HeaderFooterAsset extends BasicAssetBundle{

    public $sourcePath = '@custom/views/assets';
    public $css = [
        'css/header_footer.css',
    ];
    public $js = [
        'js/header_footer.js',
    ];

    public $depends = [
        'custom\assets\GlobalAsset',
    ];
}
