<?php
namespace admin\assets;

//use yii\web\AssetBundle;
use common\assets\BaseAssetBundle;

class GlobalAsset extends BaseAssetBundle{

    public $sourcePath = '@admin/views/assets';
    public $css = [
        'css/global.css',
        'css/cpt-goods-panel.css',
        'css/cpt-goods-info-panel.css',
        'css/cpt-classify-add-new-item-panel.css',
        'css/cpt-classify-add-secondary-item-panel.css',
        'css/cpt-brand-panel.css',
        'css/cpt-brand-info-panel.css',
        'css/cpt-set-price-for-group-buying-panel.css',
        'css/cpt-set-group-buying-panel.css',
        'css/swiper.css'
    ];
    public $js = [
        'js/global.js',
        'js/cpt-goods-panel.js',
        'js/cpt-goods-info-panel.js',
        'js/cpt-classify-add-new-item-panel.js',
        'js/cpt-classify-add-secondary-item-panel.js',
        'js/cpt-brand-panel.js',
        'js/cpt-brand-info-panel.js',
        'js/cpt-set-price-for-group-buying-panel.js',
        'js/cpt-set-group-buying-panel.js',
    ];

    public $_css = [
        'css/error.css',
    ];

    public $_js = [
        'js/error.js',
    ];

    public $depends = [
        'common\assets\SuperGlobalAsset',
    ];
}
