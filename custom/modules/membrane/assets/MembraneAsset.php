<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/26 0026
 * Time: 17:47
 */

namespace custom\modules\membrane\assets;


use common\assets\BaseAssetBundle;
use custom\assets\GlobalAsset;

class MembraneAsset extends BaseAssetBundle
{
    public $sourcePath = '@membrane/views/assets';

    public $depends = [
        GlobalAsset::class
    ];
}