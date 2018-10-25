<?php
namespace admin\assets;

use common\assets\BaseAssetBundle;

class LoginAsset extends BaseAssetBundle{

    public $sourcePath = '@admin/views/assets';
    public $css = [
        'css/login.css',
    ];
    public $js = [
        'js/login.js',
    ];

    public $depends = [
        'admin\assets\GlobalAsset',
    ];
}
