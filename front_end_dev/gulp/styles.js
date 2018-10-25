'use strict';

var path = require('path');
var gulp = require('gulp');
var conf = require('./conf');

var browserSync = require('browser-sync');

var $ = require('gulp-load-plugins')();

var _ = require('lodash');

gulp.task('css-vender',function() {
  return gulp.src([
    // path.join(conf.paths.src, '/scss/kindeditor/**/*')
  ], {'base': path.join(conf.paths.src, '/scss/')})
    .pipe(gulp.dest(path.join(conf.paths.tmp, '/serve/styles/vender')));
});

gulp.task('styles', ['css-vender'], function () {
  var sassOptions = {
    style: 'expanded'
  };

  // var injectFiles = gulp.src([
  //   path.join(conf.paths.src, '/scss/**/*.scss'),
  //   path.join('!' + conf.paths.src, '/scss/bootstrap/**/*.scss'),
  //   path.join('!' + conf.paths.src, '/scss/_bootstrap.scss'),
  //   path.join('!' + conf.paths.src, '/scss/app.scss')
  // ], { read: false });

  // var injectOptions = {
  //   transform: function(filePath) {
  //     filePath = filePath.replace(conf.paths.src + '/scss/', '');
  //     return '@import "' + filePath + '";';
  //   },
  //   starttag: '// injector',
  //   endtag: '// endinjector',
  //   addRootSlash: false
  // };


  return gulp.src([
    path.join(conf.paths.src, '/scss/app.scss')
  ])
    // .pipe($.inject(injectFiles, injectOptions))
    .pipe($.sourcemaps.init())
    .pipe($.sass(sassOptions)).on('error', conf.errorHandler('Sass'))
    .pipe($.autoprefixer()).on('error', conf.errorHandler('Autoprefixer'))
    .pipe($.sourcemaps.write())
    .pipe(gulp.dest(path.join(conf.paths.tmp, '/serve/styles/')))
    .pipe(browserSync.reload({ stream: true }));
});
