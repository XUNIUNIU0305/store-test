<?php
namespace admin\assets;

use common\assets\BaseAssetBundle;

class MenuAsset extends BaseAssetBundle{

    public $sourcePath = '@admin/views/assets';
    public $css = [
        'css/menu.css',
    ];
    public $js = [
        'js/menu.js',
    ];

    protected $_css = [
        'css/main.css',
    ];

    protected $_js = [
        'js/main.js',
    ];

    public $depends = [
        'admin\assets\GlobalAsset',
    ];
}
