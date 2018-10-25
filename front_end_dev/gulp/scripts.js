'use strict';

var path = require('path');
var gulp = require('gulp');
var conf = require('./conf');

var $ = require('gulp-load-plugins')({
    pattern: ['gulp-*', 'uglify-save-license', 'del']
});

var browserSync = require('browser-sync');

var $ = require('gulp-load-plugins')();

gulp.task('scripts-vender', function () {
    return gulp.src([
            path.join(conf.paths.src, '/js/kindeditor/**/*')
        ], {
            'base': path.join(conf.paths.src, '/js/')
        })
        .pipe(gulp.dest(path.join(conf.paths.tmp, '/serve/scripts/vender')));
});

gulp.task('scripts', ['scripts-bower', 'scripts-vender'], function () {
    return gulp.src([
            path.join(conf.paths.src, '/js/app.js'),
            path.join(conf.paths.src, '/js/admin/**/*')
        ])
        .pipe($.sourcemaps.init())
        .pipe($.concat('app.js'))
        .pipe($.sourcemaps.write('maps'))
        .pipe(gulp.dest(path.join(conf.paths.tmp, '/serve/scripts/')))
        .pipe(browserSync.reload({
            stream: true
        }))
        .pipe($.size())
});

gulp.task('scripts-bower', function () {
    return gulp.src([
            // put out-sourced (like bower) scripts here
            path.join(conf.paths.bower, '/jquery/dist/jquery.min.js'),
            path.join(conf.paths.src, '/js/jquery.jcarousel.min.js'),
            path.join(conf.paths.src, '/js/gallary-carousels.js'),
            path.join(conf.paths.src, '/js/echarts.min.js'),
            path.join(conf.paths.src, '/js/china.js'),
            path.join(conf.paths.bower, '/bootstrap-sass/assets/javascripts/bootstrap.js'),
            path.join(conf.paths.bower, '/iscroll/build/iscroll.js'),
            path.join(conf.paths.bower, '/lightbox2/dist/js/lightbox.js'),
            path.join(conf.paths.bower, '/bootstrap-select/dist/js/bootstrap-select.js'),
            path.join(conf.paths.bower, '/bootstrap-datepicker/dist/js/bootstrap-datepicker.js'),
            path.join(conf.paths.bower, '/moment/min/moment-with-locales.min.js'),
            path.join(conf.paths.bower, '/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'),
            path.join(conf.paths.src, '/js/daterangepicker.js'),
            path.join(conf.paths.src, '/js/moment.min.js'),
            path.join(conf.paths.src, '/js/highcharts.js'),
            path.join(conf.paths.src, '/js/highcharts-more.js')
        ])
        .pipe($.sourcemaps.init())
        .pipe($.concat('libs.js'))
        .pipe($.sourcemaps.write('maps'))
        .pipe(gulp.dest(path.join(conf.paths.tmp, '/serve/libs/')))
        .pipe(browserSync.reload({
            stream: true
        }))
        .pipe($.size())
})
