const { src, dest, watch, series } = require( 'gulp' );
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
  src : './src/',
  build: './'
};

function compileSCSS() {
  return src( paths.scss.src )
  .pipe( sourcemaps.init() )
  .pipe( sass().on( 'error', sass.logError ) )
  .pipe( postcss( [
    autoprefixer(),
    postcssAssets( {
      loadPaths : [ 'img/' ],
      basePath : paths.build + 'img/',
      baseUrl : './img/'
    } )

  ] ) )
  .pipe( cleanCSS() )
  .pipe( sourcemaps.write( '.' ) )
  .pipe( dest( paths.scss.dest ) );
}

function watchFiles() {
  watch( paths.scss.src, compileSCSS );
}

exports.default = series( compileSCSS, watchFiles );
