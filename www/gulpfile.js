/**
 * Created by Lal on 29.06.2016.
 */
var
    gulp = require('gulp'),
    sass = require('gulp-sass');

// source and distribution folder
var
    source = 'assets/src/',
    dest = 'public/assets/dist/';

// Bootstrap scss source
var bootstrapSass = {
    in: './node_modules/bootstrap-sass/'
};

// Bootstrap fonts source
var fonts = {
    in: [source + 'fonts/*.*', bootstrapSass.in + 'assets/fonts/**/*'],
    out: dest + 'fonts/'
};

// Our scss source folder: .scss files
var scss = {
    in: source + 'scss/main.scss',
    out: dest + 'css/',
    watch: source + 'scss/**/*',
    sassOpts: {
        outputStyle: 'nested',
        precison: 3,
        errLogToConsole: true,
        includePaths: [bootstrapSass.in + 'assets/stylesheets']
    }
};

gulp.task('fonts', function () {
    return gulp
        .src(fonts.in)
        .pipe(gulp.dest(fonts.out));
});

gulp.task('sass', ['fonts'], function () {
    return gulp.src(scss.in)
        .pipe(sass(scss.sassOpts))
        .pipe(gulp.dest(scss.out));
});

gulp.task('default', ['sass'], function () {
    gulp.watch(scss.watch, ['sass']);
});