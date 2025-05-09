const { src, dest, watch, series } = require( 'gulp' );
const concat = require( 'gulp-concat' );
const uglify = require( 'gulp-uglify-es' ).default;
const sass = require( 'gulp-sass' )( require( 'sass' ) );
const sourcemaps = require( 'gulp-sourcemaps' );
const postcss = require( 'gulp-postcss' );
const postcssAssets = require( 'postcss-assets' );
const autoprefixer = require( 'autoprefixer' );
const cleanCSS = require( 'gulp-clean-css' );

const paths = {
  scss : {
    src : 'src/scss/**/*.scss',
    dest : './'
  },
  js : {
    src : 'src/js/**/*.js',
    dest : './js/',
    filename : 'scripts.js'
  },
  root : './'
};

// JavaScript processing
function compileJS() {
  return src( [ paths.js.src ] )
  .pipe( concat( paths.js.filename ) )
  .pipe( uglify() )
  .pipe( dest( paths.js.dest ) )
  // .pipe(livereload());
}

// SCSS processing
function compileSCSS() {
  return src( paths.scss.src )
  .pipe( sourcemaps.init() )
  .pipe( sass( undefined, undefined ).on( 'error', sass.logError ) )
  .pipe( postcss( [
    autoprefixer(),
    postcssAssets( {
      loadPaths : [ 'img/' ],
      basePath : paths.root + 'img/',
      baseUrl : './img/'
    } )
  ] ) )
  .pipe( cleanCSS() )
  .pipe( sourcemaps.write( '.' ) )
  .pipe( dest( paths.scss.dest ) );
}

function watchFiles() {
  watch( paths.scss.src, compileSCSS );
  watch( paths.js.src, compileJS );
}

exports.default = series( compileSCSS, compileJS, watchFiles );
// exports.default = series( compileJS );
// exports.default = series( compileSCSS );
