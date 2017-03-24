var gulp       = require ('gulp'),
    livereload = require('gulp-livereload'),
    uglifyJS   = require ('gulp-uglify'),
    htmlmin    = require('gulp-html-minifier'),
    del        = require('del'),
    shell      = require('gulp-shell');

// ===================================================

gulp.task ('build_dev', shell.task ('php put.php'));

gulp.task ('default', function () {
  livereload.listen ();

  ['./root/views/*.php', './*.php', './libs/*.php', './root/css/**/*.css', './root/js/**/*.js'].forEach (function (t) {
    gulp.watch (t).on ('change', function () {
      gulp.run ('build_dev');
    });
  });

  ['./root/*.html'].forEach (function (t) {
    gulp.watch (t).on ('change', function () {
      gulp.run ('reload');
    });
  });
});
gulp.task ('reload', function () {
  livereload.changed ();
  console.info ('\n== ReLoad Browser! ================================================\n');
});

// ===================================================

gulp.task ('minify', function () {
  gulp.run ('js-uglify');
  gulp.run ('minify-html');
});
gulp.task ('js-uglify', function () {
  gulp.src ('./root/js/**/*.js')
      .pipe (uglifyJS ())
      .pipe (gulp.dest ('./root/js/'));
});
gulp.task ('minify-html', function () {
  gulp.src ('./root/*.html')
      .pipe (htmlmin ({collapseWhitespace: true}))
      .pipe (gulp.dest ('./root/'));
});

// ===================================================

gulp.task ('gh-pages', function () {
  del (['./root']);
});