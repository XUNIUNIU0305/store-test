<?php
namespace business\assets;

use common\assets\BaseAssetBundle;

class ModuleAsset extends BaseAssetBundle{

    public $sourcePath = '@business/views/assets';

    public $css = [
        'css/module.css',
    ];

    public $js = [
        'js/module.js',
    ];

    protected $_css = [];

    protected $_js = [];

    public $depends = [
        'business\assets\GlobalAsset',
    ];
}
