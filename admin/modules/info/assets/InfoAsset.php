<?php
namespace admin\modules\info\assets;

use common\assets\BaseAssetBundle;

class InfoAsset extends BaseAssetBundle{

    public $sourcePath = '@info/views/assets';

    public $css = [
        'css/info.css',
    ];

    public $js = [
        'js/info.js',
    ];

    protected $_css = [
        'css/order.css',
        'css/statement.css',
        'css/gpubs-detail.css',
    ];

    protected $_js = [
        'js/order.js',
        'js/statement.js',
        'js/gpubs-detail.js',
    ];

    public $depends = [
        'admin\assets\GlobalAsset',
    ];
}
