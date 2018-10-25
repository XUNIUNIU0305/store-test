<?php
namespace admin\assets;

use common\assets\BaseAssetBundle;

class OverviewAsset extends BaseAssetBundle{

    public $sourcePath = '@admin/views/assets';
    public $css = [];
    public $js = [];

    protected $_css = [
        'css/overview.css'
    ];

    protected $_js = [
        'js/overview.js'
    ];

    public $depends = [
        'admin\assets\GlobalAsset',
    ];
}
