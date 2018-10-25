<?php
namespace business\assets;

use common\assets\BaseAssetBundle;

class OverviewAsset extends BaseAssetBundle{

    public $sourcePath = '@business/views/assets';

    public $css = [];

    public $js = [];

    public $_css = [
        'css/overview.css',
    ];

    public $_js = [
        'js/overview.js',
    ];

    public $depends = [
        'business\assets\GlobalAsset',
    ];
}
