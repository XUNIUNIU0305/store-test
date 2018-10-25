<?php
namespace admin\modules\count\assets;

use common\assets\BaseAssetBundle;

class CountAsset extends BaseAssetBundle{

    public $sourcePath = '@count/views/assets';

    public $css = [
        'css/count.css',
    ];

    public $js = [
        'js/count.js',
    ];

    protected $_css = [
        'css/itemrank.css',
        'css/accountconsumption.css',
        'css/areaconsumption.css',
        'css/itemrankdetail.css',
    ];

    protected $_js = [
        'js/itemrank.js',
        'js/accountconsumption.js',
        'js/areaconsumption.js',
        'js/itemrankdetail.js',
    ];

    public $depends = [
        'admin\assets\GlobalAsset',
    ];
}
