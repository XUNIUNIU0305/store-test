<?php
namespace supply\assets;

use common\assets\BaseAssetBundle;

class PrintAsset extends BaseAssetBundle{

    public $sourcePath = '@supply/views/assets';
    public $css = [];
    public $js = [];
    public $_css = [
        'css/print.css',
    ];
    public $_js = [
        'js/print.js',
    ];

    public $depends = [
        'supply\assets\GlobalAsset',
    ];

}
