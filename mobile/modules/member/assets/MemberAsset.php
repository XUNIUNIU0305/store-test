<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/17
 * Time: 14:25
 */

namespace mobile\modules\member\assets;


use common\assets\BaseAssetBundle;

class MemberAsset extends BaseAssetBundle
{
    public $sourcePath = '@mobile/modules/member/views/assets';
    public $css = [
    ];
    public $js = [
    ];

    public $_css = [
        'css/login.css',
        'css/address.css',
        'css/account.css',
        'css/orderDetail.css',
        'css/orderItem.css',
        'css/coupon-index.css',
        'css/coupon-active.css',
        'css/order-list.css',
        'css/register.css',
        'css/invite-index.css',
        'css/invite-list.css',
        'css/water.css',
        'css/auth.css',
        'css/account-express.css',
        'css/activity-register.css',
        'css/gpubs-order-list.css',
        'css/gpubs-order-detail.css',
        'css/gpubs-pick-list.css',
        'css/gpubs-pick-detail.css',
        'css/address-gpubs-add.css',
        'css/address-gpubs-index.css',
        'css/franchiser-index.css',
        'css/repay.css',
        'css/djy-user.css',
        'css/djy-order.css',
    ];

    public $_js = [
        'js/qrcode.js',
        'js/clipboard.min.js',
        'js/account-index.js',
        'js/account-login.js',
        'js/address-list.js',
        'js/order-detail.js',
        'js/address-add.js',
        'js/coupon-index.js',
        'js/coupon-active.js',
        'js/order-list.js',
        'js/register.js',
        'js/invite-index.js',
        'js/invite-list.js',
        'js/water.js',
        'js/auth.js',
        'js/account-express.js',
        'js/activity-register.js',
        'js/gpubs-order-list.js',
        'js/gpubs-order-detail.js',
        'js/gpubs-pick-list.js',
        'js/gpubs-pick-detail.js',
        'js/address-gpubs-add.js',
        'js/address-gpubs-index.js',
        'js/franchiser-index.js',
        'js/repay.js',
        'js/djy-user.js',
        'js/djy-order.js',
    ];
    public $depends = [
        'mobile\assets\GlobalAsset',
    ];


}
