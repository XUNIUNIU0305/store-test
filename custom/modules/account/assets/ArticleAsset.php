<?php
namespace custom\modules\account\assets;

use common\assets\BaseAssetBundle;

class ArticleAsset extends BaseAssetBundle{

    public $sourcePath = '@account/views/assets';
    public $css = [];
    public $js = [];
    public $_css = [
        'css/article_wechat.css',
    ];
    public $_js = [
        'js/article_wechat.js',
    ];

    public $depends = [
        'custom\assets\GlobalAsset',
    ];

}
