<?php
namespace mobile\assets;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle{

    public $sourcePath = '@mobile/views/assets';
    public $css = [
        'css/global.css',
    ];
    public $js = [
        'js/global.js',
        'js/commonApi.js',
        'js/scroll.js',
        'https://cdn.bootcss.com/purl/2.3.1/purl.min.js'
    ];

    public $depends = [
        'common\assets\basic\JuicerAsset',
        'common\assets\basic\ZeptoAsset',
        'common\assets\basic\FastclickAsset'
    ];
}
