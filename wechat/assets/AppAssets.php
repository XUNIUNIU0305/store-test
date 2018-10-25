<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/3
 * Time: 15:54
 */

namespace wechat\assets;


use yii\web\AssetBundle;

class AppAssets extends AssetBundle
{
    public $sourcePath = '@wechat/views/assets';

    public $js = [
        'app.js'
    ];
}