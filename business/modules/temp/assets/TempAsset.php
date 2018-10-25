<?php
namespace business\modules\temp\assets;

use common\assets\BaseAssetBundle;

class TempAsset extends BaseAssetBundle{

    public $sourcePath = '@temp/views/assets';

    public $css = [
        'css/temp.css',
    ];

    public $js = [
        'js/temp.js',
    ];

    protected $_css = [
        'css/exchange.css',
        'css/list.css',
    ];

    protected $_js = [
        'js/exchange.js',
        'js/list.js',
    ];

    public $depends = [
        'business\assets\ModuleAsset',
    ];
}
