var gulp       = require('gulp');
var livereload = require('gulp-livereload');
var concat     = require('gulp-concat');
var less       = require('gulp-less');
var minifyCss  = require('gulp-minify-css');
var exec       = require('gulp-exec');
var clean      = require('gulp-clean');

/*
* All paths
*/
var paths = {
    php: ['**/*.php', '!vendor/**/*', '!phpcs/**/*', '!node_modules/**/*'],
    less: ['web/less/**/*'],
    css: ['web/css/**/*', '!web/css/light.css'],
    dist: ['dist']
};

/*
* Clean
*/
gulp.task('clean', function() {
    return gulp.src(paths.dist, {read: false})
        .pipe(clean());
});

/*
* Build css & clean
*/
gulp.task('buildcss', ['css'], function() {
    return gulp.src(paths.css, {read: false})
        .pipe(clean());
});

/*
* Compile less files
*/
gulp.task('less', function() {
    return gulp.src(paths.less)
        .pipe(less())
        .pipe(gulp.dest('web/css'));
});

/*
* Minify CSS
*/
gulp.task('minifyCss', ['less'], function() {
    return gulp.src(paths.css)
        .pipe(minifyCss())
        .pipe(gulp.dest('web/css'));
});

/*
* Concat css
*/
gulp.task('css', ['minifyCss'], function() {
    return gulp.src(paths.css)
        .pipe(concat('light.css', {newLine: ''}))
        .pipe(gulp.dest('web/css'));
});

/*
* Watch all saved files
*/
gulp.task('watch', function() {
    var server = livereload();
    gulp.watch('web/less/**/*', ['less']);

    gulp.watch(['*.html', '*.js', 'web/css/*.css']).on('change', function(e) {
        console.log(e);
        server.changed(e.path);
    });
});

/*
* PHP code sniffer without warnings
*/
gulp.task('phpcs-n', function() {
    return gulp.src(paths.php)
        .pipe(exec('phpcs -n <%= file.path %>'));
});

/*
* PHP code sniffer with full log
*/
gulp.task('phpcs', function() {
    return gulp.src(paths.php)
        .pipe(exec('phpcs <%= file.path %>'));
});

/*
* Gulp default task
*/
gulp.task('default', ['clean', 'buildcss'], function() {
    return gulp.src(['**/.htaccess', 'app/**/*', 'src/**/*', 'vendor/**/*', 'web/*.*', 'web/css/*'], {base: './'})
        .pipe(gulp.dest('dist'));
});
