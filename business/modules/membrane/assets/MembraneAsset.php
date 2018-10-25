<?php
/**
 * Created by PhpStorm.
 * User: qingqiu
 * Date: 2017/7/26 0026
 * Time: 17:44
 */

namespace business\modules\membrane\assets;


use business\assets\GlobalAsset;
use common\assets\BaseAssetBundle;

class MembraneAsset extends BaseAssetBundle
{
    public $sourcePath = '@membrane/views/assets';

    public $js = [
        'js/index.js'
    ];

    public $css = [
        'css/index.css'
    ];

    public $depends = [
        GlobalAsset::class
    ];
}