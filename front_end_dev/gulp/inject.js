'use strict';

var path = require('path');
var gulp = require('gulp');
var conf = require('./conf');

var $ = require('gulp-load-plugins')();

var browserSync = require('browser-sync');
var _ = require('lodash');

gulp.task('inject', ['htmlInclude', 'scripts', 'styles'], function () {
    var injectStyles = gulp.src([
        path.join(conf.paths.tmp, '/serve/styles/*.css'),
        path.join(conf.paths.tmp, '/serve/libs/*.js'),
        path.join(conf.paths.tmp, '/serve/scripts/*.js'),
        path.join('!' + conf.paths.tmp, '/serve/vendor.css'),
        path.join('!' + conf.paths.tmp, '/serve/vendor.js')
    ], {
        read: false
    });

    var injectOptions = {
        ignorePath: [conf.paths.src, path.join(conf.paths.tmp, '/serve')],
        addRootSlash: true
    };

    return gulp.src(path.join(conf.paths.tmp, '/serve/html/**/*.html'))
        .pipe($.inject(injectStyles, injectOptions))
        .pipe(gulp.dest(path.join(conf.paths.tmp, '/serve/html/')))
        .pipe(browserSync.reload({
            stream: true
        }));
});
