<?php
namespace custom\modules\corporation\assets;

use common\assets\BaseAssetBundle;

class EmptyAsset extends BaseAssetBundle{

    public $sourcePath = '@corporation/views/assets';
    public $css = [];
    public $js = [];
    public $_css = [
        'css/employ.css',
    ];
    public $_js = [
        'js/employ.js',
    ];

    public $depends = [
        'custom\assets\HeaderFooterSearchAsset',
    ];
}
