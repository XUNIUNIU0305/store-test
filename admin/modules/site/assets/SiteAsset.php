<?php
namespace admin\modules\site\assets;

use common\assets\BaseAssetBundle;

class SiteAsset extends BaseAssetBundle{

    public $sourcePath = '@site/views/assets';

    public $css = [
        'css/site.css',
    ];

    public $js = [
        'js/site.js',
    ];

    protected $_css = [
        'css/category.css',
        'css/registercode.css',
        'css/role.css',
        'css/carousel.css',
        'css/floor.css',
        'css/business.css',
        'css/employee.css',
        'css/refund.css',
        'css/brandindex.css',
        'css/shopindex.css',
        'css/article_index.css',
        'css/article_create.css',
        'css/user.css',
        'css/add-new-floor.css',
        'css/wap-shopindex.css',
        'css/site-index.css'
    ];

    protected $_js = [
        'js/category.js',
        'js/registercode.js',
        'js/role.js',
        'js/carousel.js',
        'js/floor.js',
        'js/department.js',
        'js/business.js',
        'js/employee.js',
        'js/refund.js',
        'js/brandindex.js',
        'js/shopindex.js',
        'js/article_index.js',
        'js/article_create.js',
        'js/user.js',
        'js/add-new-floor.js',
        'js/wap-shopindex.js',
        'js/site-index.js' 
    ];

    public $depends = [
        'admin\assets\GlobalAsset',
    ];
}
