var gulp = require('gulp');
var less = require('gulp-less');
var rename = require('gulp-rename');
var cleancss = require('gulp-clean-css');
var autoprefixer = require('gulp-autoprefixer');

var lessPath = 'src/Buttons/template/less';
var lessEntry = lessPath + '/*.less';
var lessAll = lessPath + '/**/*.less';

gulp.task('dist-css', function(){
  return gulp.src(lessEntry)
    .pipe(less())
    .pipe(autoprefixer({ browsers: ['last 2 versions'] }))
    .pipe(cleancss())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest('dist'));
});

gulp.task('css', function() {
  return gulp.src(lessEntry)
    .pipe(less()).on('error', function(error) {
      console.log(error.toString());
      this.emit('end');
    })
    .pipe(gulp.dest('tests'));
});

gulp.task('watch', function() {
  return gulp.watch(lessAll, ['css']);
});
