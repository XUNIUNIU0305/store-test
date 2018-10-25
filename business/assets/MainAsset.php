<?php
namespace business\assets;

use common\assets\BaseAssetBundle;

class MainAsset extends BaseAssetBundle{

    public $sourcePath = '@business/views/assets';

    public $css = [];

    public $js = [];

    public $_css = [
        'css/main.css',
    ];

    public $_js = [
        'js/main.js',
    ];

    public $depends = [
        'business\assets\GlobalAsset',
    ];
}
