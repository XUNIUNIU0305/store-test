<?php
namespace business\modules\leader\assets;

use common\assets\BaseAssetBundle;

class LeaderAsset extends BaseAssetBundle{

    public $sourcePath = '@leader/views/assets';

    public $css = [
        'css/leader.css',
    ];

    public $js = [
        'js/leader.js',
    ];

    protected $_css = [
        'css/person.css',
        'css/area.css',
        'css/custom.css',
        'css/custom-list.css',
        'css/custom-quantity.css',
    ];

    protected $_js = [
        'js/person.js',
        'js/area.js',
        'js/custom.js',
        'js/custom-list.js',
        'js/custom-quantity.js',
    ];

    public $depends = [
        'business\assets\ModuleAsset',
    ];
}
