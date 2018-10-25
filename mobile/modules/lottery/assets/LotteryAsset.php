<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 17-9-30
 * Time: 上午11:59
 */

namespace mobile\modules\lottery\assets;

use yii\web\AssetBundle;

class LotteryAsset extends AssetBundle
{
    public $sourcePath = '@lottery/views/assets';

    public $js = [
        'js/vendors.js'
    ];
}