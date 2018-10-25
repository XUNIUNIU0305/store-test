<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-10-23
 * Time: 上午11:07
 */

namespace supply\modules\express\assets;

use yii\web\AssetBundle;
use supply\assets\MainAsset;

class ExpressAsset extends AssetBundle
{
    public $sourcePath = '@express/views/assets';

    public $depends = [
        MainAsset::class
    ];
}