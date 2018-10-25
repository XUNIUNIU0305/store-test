<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/31 0031
 * Time: 16:59
 */

namespace mobile\modules\customization\assets;


use common\assets\BaseAssetBundle;
use mobile\assets\GlobalAsset;

class Asset extends BaseAssetBundle
{
    public $sourcePath = '@customization/views/assets';

    public $depends = [
        GlobalAsset::class
    ];
}