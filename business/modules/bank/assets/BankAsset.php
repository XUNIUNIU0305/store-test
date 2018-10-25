<?php
namespace business\modules\bank\assets;

use common\assets\BaseAssetBundle;

class BankAsset extends BaseAssetBundle{

    public $sourcePath = '@bank/views/assets';

    public $css = [
        'css/bank.css',
    ];

    public $js = [
        'js/bank.js',
    ];

    protected $_css = [
        'css/card.css',
        'css/open-account.css',
        'css/draw-apply.css',
        'css/draw-list.css',
        'css/draw-detail.css',
    ];

    protected $_js = [
        'js/card.js',
        'js/open-account.js',
        'js/draw-apply.js',
        'js/draw-list.js',
        'js/draw-detail.js',
    ];

    public $depends = [
        'business\assets\ModuleAsset',
    ];
}
