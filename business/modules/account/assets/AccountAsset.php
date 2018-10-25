<?php
namespace business\modules\account\assets;

use common\assets\BaseAssetBundle;

class AccountAsset extends BaseAssetBundle{

    public $sourcePath = '@user/views/assets';

    public $css = [
        'css/account.css',
    ];

    public $js = [
        'js/account.js',
    ];

    protected $_css = [
        'css/index.css',
        'css/statement-index.css'
    ];

    protected $_js = [
        'js/index.js',
        'js/statement-index.js'
    ];

    public $depends = [
        'business\assets\ModuleAsset',
    ];
}
