<?php
namespace admin\modules\fund\assets;

use common\assets\BaseAssetBundle;

class FundAsset extends BaseAssetBundle{

    public $sourcePath = '@fund/views/assets';

    public $css = [
        'css/fund.css',
    ];

    public $js = [
        'js/fund.js',
    ];

    protected $_css = [
        'css/deposit-and-draw-list.css',
        'css/deposit-and-draw-detail.css',
        'css/deposit-and-draw-application.css',
    ];

    protected $_js = [
        'js/deposit-and-draw-list.js',
        'js/deposit-and-draw-detail.js',
        'js/deposit-and-draw-application.js',
    ];

    public $depends = [
        'admin\assets\GlobalAsset',
    ];
}
