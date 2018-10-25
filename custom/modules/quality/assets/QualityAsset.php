<?php
namespace custom\modules\quality\assets;

use common\assets\BaseAssetBundle;

class QualityAsset extends BaseAssetBundle{

    public $sourcePath = '@quality/views/assets';
    public $css = [
    ];
    public $js = [
    ];
    public $_css = [
        'css/price.css',
    ];
    public $_js = [
        'js/price.js',

    ];

    public $depends = [
        'custom\assets\HeaderFooterSearchAsset',
    ];
}
