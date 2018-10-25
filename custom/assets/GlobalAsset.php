<?php
namespace custom\assets;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle{

    public $sourcePath = '@custom/views/assets';
    public $css = [
        'css/global.css',
    ];
    public $js = [
        'js/global.js',
        'js/bootstrap-select.min.js'
    ];

    public $depends = [
        'common\assets\SuperGlobalAsset',
    ];
}
