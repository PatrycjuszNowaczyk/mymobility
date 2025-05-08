'use strict';

const

  // source and build folders
  dir = {
    src         : './src/',
    build       : './'
  },

  // Gulp and plugins
  gulp          = require('gulp'),
//  clean         = require('gulp-clean'),
  gutil         = require('gulp-util'),
  newer         = require('gulp-newer'),
  // imagemin      = require('gulp-imagemin'),
  sass          = require('gulp-sass'),
  postcss       = require('gulp-postcss'),
  // deporder      = require('gulp-deporder'),
  concat        = require('gulp-concat'),
  stripdebug    = require('gulp-strip-debug'),
  uglify = require('gulp-uglify-es').default,
  livereload    = require('gulp-livereload'),
  gcmq          = require('gulp-group-css-media-queries'),
//  gutil         = require( 'gulp-util' ),
  ftp           = require( 'vinyl-ftp' ),
  cleanCSS = require('gulp-clean-css'),
  sourcemaps = require('gulp-sourcemaps');

// Browser-sync
var browsersync = false;

// image settings
const images = {
  src         : dir.src   + 'img/**/*',
  build       : dir.build + 'img/'
};

// image processing
gulp.task('images', () => {
  return gulp.src(images.src)
    .pipe(newer(images.build))
    // .pipe(imagemin())
    .pipe(gulp.dest(images.build));
});

// CSS settings
var css = {
  src         : dir.src + 'css/style.scss',
  watch       : dir.src + 'css/**/*',
  build       : dir.build,
  sassOpts: {
    outputStyle     : 'nested',
    imagePath       : images.build,
    precision       : 3,
    errLogToConsole : true
  },
  processors: [
    require('postcss-assets')({
      loadPaths: ['img/'],
      basePath: dir.build + 'img/',
      baseUrl: './img/'
    }),
    require('autoprefixer')({
      browsers: ['last 2 versions', '> 2%']
    }),
    require('css-mqpacker'),
    require('cssnano')
  ]
};

// CSS processing
gulp.task('css', ['images'], () => {
  return gulp.src(css.src)
    .pipe(sass(css.sassOpts))
    .pipe(postcss(css.processors))
    .pipe(gcmq())
    .pipe(sourcemaps.init())
    .pipe(cleanCSS())
    .pipe(sourcemaps.write())
    .pipe(gulp.dest(css.build))
    .pipe(livereload());
});

// JavaScript settings
const js = {
  src         : dir.src + 'js/**/*',
  build       : dir.build + 'js/',
  filename    : 'scripts.js'
};

// JavaScript processing
gulp.task('js', () => {
//  return gulp.src(js.src)
  return gulp.src([
//    'js/lib/jquery.js',
//    'js/lib/bxslider.js',
    // 'js/lib/owl-carousel.js',
//    'js/lib/jquery-ui.min.js',
//    'js/lib/lightslider.js',
    // 'js/lib/fancybox.js',
//    'js/lib/validate.min.js',
//    'js/lib/videojs.js',
    // 'js/lib/isotope.pkgd.js',
    // 'js/lib/jquery.nicescroll.js',
    
//    'js/lib/waypoints.min.js',
//    'js/lib/countup.js',


//    'js/lib/image-zoom.js',

//    'js/lib/rAF.js',
//    'js/lib/ResizeSensor.js',
//    'js/lib/sticky-sidebar.js',

    'src/js/**/*.js'
    ])
    // .pipe(deporder())
    .pipe(concat(js.filename))
//    .pipe(stripdebug())
    .pipe(uglify())
    .pipe(gulp.dest(js.build))
    .pipe(livereload());
});


// run all tasks
gulp.task('build', ['css', 'js']);


//watch - live changes
gulp.task('watch', function() {
  livereload.listen(35729);
  gulp.watch('**/*.php').on('change', function(file) {
    livereload.changed(file.path);
  });
    gulp.watch(dir.src + 'css/**/*.scss', ['css']);
    gulp.watch(dir.src + 'js/**/*.js', ['js']);
});


// default task
gulp.task('default', ['build', 'watch']);

