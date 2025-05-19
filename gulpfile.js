const { src, dest, watch, series } = require( 'gulp' );
const concat = require( 'gulp-concat' );
const uglify = require( 'gulp-uglify-es' ).default;
const sass = require( 'gulp-sass' )( require( 'sass' ) );
const sourcemaps = require( 'gulp-sourcemaps' );
const postcss = require( 'gulp-postcss' );
const postcssAssets = require( 'postcss-assets' );
const autoprefixer = require( 'autoprefixer' );
const cleanCSS = require( 'gulp-clean-css' );
const browserSync = require( 'browser-sync' ).create();

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

// Initialize BrowserSync
function browserSyncInit( done ) {
  browserSync.init( {
    // Proxy requests to your Docker container's web server
    // Replace 'localhost:8000' with the actual address and port your Docker container exposes
    // e.g., if your Docker container maps port 80 to your host's port 8080, you'd use 'localhost:8080'
    proxy : 'http://localhost', // <-- IMPORTANT: Configure this to your Docker setup
    open : true, // Automatically open the browser
    // It's often helpful to set a specific port for BrowserSync UI if you have multiple services running
    ui : {
      port : 3001
    },
    host : 'http://localhost',
    browser : 'firefox',
    files : [
      "./style.css",
      // JS scripts commented out because I want to manually reload a page due to unwanted websocket XHR requests in dev tools
      // "./js/scripts.js"
    ],
    reloadOnRestart : true,
    reloadDebounce : 2000
  } );
  done();
}

// BrowserSync reload task
function browserSyncReload( done ) {
  // browserSync.reload();
  // browserSync.stream();
  browserSync.reload( {
      stream : false
    }
  );
  done();
}

// JavaScript processing
function compileJS() {
  return src( [ paths.js.src ] )
  .pipe( concat( paths.js.filename ) )
  .pipe( uglify( { compress : { drop_console : true } } ) )
  .pipe( dest( paths.js.dest ) )
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
  .pipe( dest( paths.scss.dest ) )
}

function watchFiles() {
  watch( paths.scss.src, series( compileSCSS ) ); // Use browserSyncReload for full page refresh on SCSS changes if needed
  watch( paths.js.src, series( compileJS ) );   // Ensure JS changes trigger a full reload
  // watch( [ './js/scripts.js', './style.css' ], browserSyncReload );
  // Add this if you want to reload on HTML/PHP file changes as well,
  // ensure these paths are accessible from where Gulp is running or adjust accordingly.
  // watch( ['./*.html', './**/*.php'], browserSyncReload );
}

exports.default = series( compileSCSS, compileJS, browserSyncInit, watchFiles );