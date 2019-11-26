'use strict';

import gulp from 'gulp';
import babel from 'gulp-babel';

var plugins = require('gulp-load-plugins')();

var pathto = function (file) {
      return ('./public/' + file);
    };

var scripts = {
      //src: pathto('javascripts/*.js'),
      src: [pathto('javascripts/scripts.js'), pathto('javascripts/index.js'), pathto('javascripts/payment.js')],
      dest: pathto('dist')
    };

var styles = {
      src: [pathto('stylesheets/*.scss'), "!"+pathto('stylesheets/_*.scss')],
      scss: pathto('stylesheets/**/*.scss'),
      dest: pathto('dist')
    };

const compileMarkup = () => { /* COMPILE MARKUP */ }

const compileScript = () => {
  return gulp.src(scripts.src)
  .pipe(babel())
  .pipe(plugins.concat('app.js'))
  .pipe(plugins.uglify())
  .pipe(gulp.dest(scripts.dest));
}

const compileStyle = () => {
  return gulp.src(styles.src)
  .pipe(plugins.sass())
  .pipe(plugins.csscomb())
  .pipe(plugins.cleanCss())
  .pipe(gulp.dest(styles.dest));
}

const clean = (done) => {
  return done()
}

const watchMarkup = (done) => {
  return done()
}

const watchScript = () => {
  return gulp.watch(scripts.src, compileScript);
}

const watchStyle = () => {
  return gulp.watch(styles.scss, compileStyle);
}

const compile = gulp.parallel(compileMarkup, compileScript, compileStyle)
compile.description = 'compile all sources'

// Not exposed to CLI
const startServer = (done) => {
  return done()
}

const serve = gulp.series(compile, startServer)
serve.description = 'serve compiled source on local server at port 3000'

const watch = gulp.parallel(watchMarkup, watchScript, watchStyle)
watch.description = 'watch for changes to all source'

const defaultTasks = (done) => {
  return gulp.series(clean, gulp.parallel(serve, watch))(done);
};

export {
  compile,
  compileMarkup,
  compileScript,
  compileStyle,
  serve,
  watch,
  watchMarkup,
  watchScript,
  watchStyle,
}

export default defaultTasks
