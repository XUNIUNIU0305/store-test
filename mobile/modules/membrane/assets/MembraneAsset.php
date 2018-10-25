<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/31 0031
 * Time: 14:24
 */

namespace mobile\modules\membrane\assets;


use common\assets\BaseAssetBundle;
use mobile\assets\GlobalAsset;

class MembraneAsset extends BaseAssetBundle
{
    public $sourcePath = '@membrane/views/assets';

    public $depends = [
        GlobalAsset::class
    ];
}