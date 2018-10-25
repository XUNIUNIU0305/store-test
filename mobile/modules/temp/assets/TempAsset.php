<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/17
 * Time: 14:25
 */

namespace mobile\modules\temp\assets;


use common\assets\BaseAssetBundle;

class TempAsset extends BaseAssetBundle
{
    public $sourcePath = '@mobile/modules/temp/views/assets';
    public $css = [
        'css/temp.css',
        'https://cdn.bootcss.com/Swiper/4.0.7/css/swiper.min.css',
        'https://cdn.bootcss.com/animate.css/3.5.2/animate.min.css'
    ];
    public $js = [
        'js/temp.js',
        'https://cdn.bootcss.com/Swiper/4.0.7/js/swiper.min.js'
    ];

    public $_css = [
        'css/a.css',
        'css/b.css',
        'css/c.css',
        'css/d.css',
        'css/e.css',
        'css/f.css',
        'css/g.css',
        'css/h.css',
        'css/i.css',
        'css/j.css',
        'css/k.css',
        'css/l.css',
        'css/m.css',
        'css/n.css',
        'css/o.css',
        'css/p.css',
        'css/q.css',
        'css/r.css',
        'css/s.css',
        'css/t.css',
        'css/u.css',
        'css/v.css',
        'css/w.css',
        'css/x.css',
        'css/y.css',
        'css/z.css',
        'css/a2.css',
        'css/b2.css',
        'css/c2.css',
        'css/d2.css',
        'css/e2.css',
        'css/f2.css',
        'css/g2.css',
        'css/h2.css',
        'css/i2.css',
        'css/j2.css',
        'css/k2.css',
        'css/l2.css',
        'css/m2.css',
        'css/n2.css',
        'css/o2.css',
        'css/p2.css',
        'css/q2.css',
        'css/r2.css',
        'css/s2.css',
        'css/t2.css',
        'css/u2.css',
        'css/v2.css',
        'css/w2.css',
        'css/x2.css',
        'css/y2.css',
        'css/z2.css',
        'css/spike.css',
        'css/invite_index.css',
        'css/invite_confirm.css',
        'css/groupbuy-index.css',
        'css/activity.css',
        'css/rank.css',
        'css/wire.css',
    ];

    public $_js = [
        'js/a.js',
        'js/b.js',
        'js/c.js',
        'js/d.js',
        'js/e.js',
        'js/f.js',
        'js/g.js',
        'js/h.js',
        'js/i.js',
        'js/j.js',
        'js/k.js',
        'js/l.js',
        'js/m.js',
        'js/n.js',
        'js/o.js',
        'js/p.js',
        'js/q.js',
        'js/r.js',
        'js/s.js',
        'js/t.js',
        'js/u.js',
        'js/v.js',
        'js/w.js',
        'js/x.js',
        'js/y.js',
        'js/z.js',
        'js/a2.js',
        'js/b2.js',
        'js/c2.js',
        'js/d2.js',
        'js/e2.js',
        'js/f2.js',
        'js/g2.js',
        'js/h2.js',
        'js/i2.js',
        'js/j2.js',
        'js/k2.js',
        'js/l2.js',
        'js/m2.js',
        'js/n2.js',
        'js/o2.js',
        'js/p2.js',
        'js/q2.js',
        'js/r2.js',
        'js/s2.js',
        'js/t2.js',
        'js/u2.js',
        'js/v2.js',
        'js/w2.js',
        'js/x2.js',
        'js/y2.js',
        'js/z2.js',
        'js/spike.js',
        'js/invite_index.js',
        'js/invite_confirm.js',
        'js/groupbuy-index.js',
        'js/activity.js',
        'js/rank.js',
        'js/wire.js',
    ];
    public $depends = [
        'mobile\assets\GlobalAsset',
    ];


}
