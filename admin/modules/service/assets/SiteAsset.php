<?php
namespace admin\modules\service\assets;

use common\assets\BaseAssetBundle;

class SiteAsset extends BaseAssetBundle{

    public $sourcePath = '@service/views/assets';

    public $css = [

    ];

    public $js = [
    ];

    protected $_css = [
        'css/refund.css',
        'css/auth-index.css',
        'css/auth-detail.css',
        'css/auth-void.css',
    ];

    protected $_js = [
        'js/refund.js',
        'js/auth-index.js',
        'js/auth-detail.js',
        'js/auth-void.js',
    ];

    public $depends = [
        'admin\assets\GlobalAsset',
    ];
}
