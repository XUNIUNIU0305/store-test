/**
 *  This file contains the variables used in other gulp files
 *  which defines tasks
 *  By design, we only put there very generic config values
 *  which are used in several places to keep good readability
 *  of the tasks
 */

var gutil = require('gulp-util');

/**
 *  The main paths of your project handle these with care
 */
exports.paths = {
    bower: 'bower_components',
    src: 'src',
    wechat: 'src/wechat',
    dist: 'dist',
    tmp: '.tmp',
    release: function (folder, type) {
        if (folder === 'global' && type === 'css') return '../common/assets/SuperGlobalAssets/css/';
        else if (folder === 'account') return '../custom/modules/account/views/assets/css/';
        else if (folder === 'customTemp') return '../custom/modules/temp/views/assets/css/';
        else if (folder === 'adminIframe') return '../admin/modules/site/views/assets/css/';
        else if (folder === 'adminFund') return '../admin/modules/fund/views/assets/css/';
        else if (folder === 'adminCount') return '../admin/modules/count/views/assets/css/';
        else if (folder === 'adminInfo') return '../admin/modules/info/views/assets/css/';
        else if (folder === 'adminService') return '../admin/modules/service/views/assets/css/';
        else if (folder === 'adminActivity') return '../admin/modules/activity/views/assets/css/';
        else if (folder === 'adminNanjing') return '../admin/modules/nanjing/views/assets/css/';
        else if (folder === 'customQuality') return '../custom/modules/quality/views/assets/css/';
        else if (folder === 'businessLeader') return '../business/modules/leader/views/assets/css/';
        else if (folder === 'businessSite') return '../business/modules/site/views/assets/css/';
        else if (folder === 'businessAccount') return '../business/modules/account/views/assets/css/';
        else if (folder === 'businessTemp') return '../business/modules/temp/views/assets/css/';
        else if (folder === 'business-membrane') return '../business/modules/membrane/views/assets/css/';
        else if (folder === 'businessData') return '../business/modules/data/views/assets/css/';
        else if (folder === 'businessBank') return '../business/modules/bank/views/assets/css/';
        else if (folder === 'app-account') return '../mobile/modules/member/views/assets/css/';
        else if (folder === 'app-gpubs') return '../mobile/modules/gpubs/views/assets/css/';
        else if (folder === 'app-temp') return '../mobile/modules/temp/views/assets/css/';
        else if (folder === 'app-membrane') return '../mobile/modules/membrane/views/assets/css/';
        else if (folder === 'businessQuality') return '../business/modules/quality/views/assets/css/';
        else if (folder === 'adminWechat') return '../admin/web/wechat-assets/css/';
        else if (folder === 'custom-membrane') return '../custom/modules/membrane/views/assets/css/';
        else if (folder === 'app-customization') return '../mobile/modules/customization/views/assets/css/';
        else if (type === 'vender') return '../' + folder + '/web/vender/';
        else if (type === 'others') return '../' + folder + '/web/';


        else return '../' + folder + '/views/assets/' + type;
    }
};

/**
 *  Common implementation for an error handler of a Gulp plugin
 */
exports.errorHandler = function (title) {
    'use strict';

    return function (err) {
        gutil.log(gutil.colors.red('[' + title + ']'), err.toString());
        this.emit('end');
    };
};
