<?php
namespace mobile\modules\gpubs\assets;

use common\assets\BaseAssetBundle;

class GpubsAsset extends BaseAssetBundle{

    public $sourcePath = '@mobile/modules/gpubs/views/assets';

    public $css = [
        'css/gpubs.css',
    ];

    public $js = [
        'js/gpubs.js',
    ];

    public $_css = [
        'css/detail.css',
        'css/confirm.css',
        'css/share.css',
        'css/pick.css',
        'css/order.css',
        'css/jquery.flipcountdown.css',
        'css/member_info.css',
        'css/inviting_friends.css',
    ];

    public $_js = [
        'js/detail.js',
        'js/confirm.js',
        'js/share.js',
        'js/pick.js',
        'js/order.js',
        'js/jquery.flipcountdown.js',
        'js/member_info.js',
        'js/inviting_friends.js',
    ];

    public $depends = [
        'mobile\assets\GlobalAsset',
    ];
}
