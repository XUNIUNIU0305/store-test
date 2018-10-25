<?php
namespace supply\assets;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle{

    public $sourcePath = '@supply/views/assets';
    public $css = [
        'css/global.css',
    ];
    public $js = [
        'js/global.js',
    ];

    public $depends = [
        'common\assets\SuperGlobalAsset',
    ];
}
