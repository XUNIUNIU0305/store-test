<?php
namespace business\assets;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle{

    public $sourcePath = '@business/views/assets';

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
