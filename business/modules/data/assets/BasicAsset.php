<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-8-21
 * Time: 上午11:14
 */

namespace business\modules\data\assets;


use business\assets\GlobalAsset;
use yii\web\AssetBundle;

class BasicAsset extends AssetBundle
{
    public $sourcePath = '@data/views/assets';

    public $js = [
        'js/main.js'
    ];

    public $css = [
        'css/main.css'
    ];

    public $depends = [
        GlobalAsset::class
    ];
}