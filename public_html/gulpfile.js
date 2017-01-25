/**
 * На данном этапе удут компилироваться только jsx файлыю
 * Включена поддержка ES6
 * @type Module gulp|Module gulp
 */

var gulp = require('gulp'),
    clean = require('gulp-clean'),
    babel = require('gulp-babel'),
    react = require('babel-preset-react'),
    browserify = require('browserify'),
    babelify = require('babelify'),
    source = require('vinyl-source-stream'),
    transform = require('vinyl-transform'),
    uglify = require('gulp-uglify'),
    glob = require('glob'),
    es  = require('event-stream'),    
    rename     = require('gulp-rename');


var paths = {
    
    src: { //Пути откуда брать исходники        
        jsx: 'frontend/web/js/react-components/**/*.jsx',
        js: 'frontend/web/js/**/*.js',
    },
    
    build: { //Тут мы укажем куда складывать готовые после сборки файлы        
        jsx: 'frontend/web/dist/js',
        js: 'frontend/web/dist/js',
    },    
    watch: { //Тут мы укажем, за изменением каких файлов мы хотим наблюдать
        jsx: 'frontend/web/js/react-components/**/*.jsx',
    },
    clean: 'frontend/web/dist/'
};

var slash2dash = function(str) { return str.replace(/\//g,'--'); } // Небольшая функция для преобразования слешей в тирэ
var exclude_ext = function(path, ext) { return path.substr(0, path.indexOf('.'+ext)); }; // Функция для исключения расширения из имени файла

//Получить имя файла
var fname = function(path_to_file){    
    return path_to_file.replace(/^.*[\\\/]/, '');
};


/**
 * Компилируем все .jsx(es6 & es5) 
 */
gulp.task('jsx:build', function (done) { 
     
     glob(paths.src.jsx, function(err, files) {
         
        if(err) done(err);
        

        var tasks = files.map(function(entry) {
            
            // Вычесляем имя файла, чтоб задать скомпилированному файлу то же имя
            var filename = (slash2dash(entry));
            console.log(entry)
//                return gulp.src(entry).
//                        pipe(babel({
//                            plugins: ['transform-react-jsx'],
//                            presets: ['es2015', 'react']
//                        }))
//                        .pipe(rename({
//                            basename: filename,
//                            extname: '.js'
//                        }))
//                        .pipe(gulp.dest(paths.build.jsx));
            
            return browserify({ entries: [entry], extensions: ['.jsx'], debug: false })
                .transform('babelify', {presets: ['es2015', 'react']})
                .require('./'+entry,{ expose: exclude_ext(fname(entry), 'jsx')})
                .bundle()
                .pipe(source(filename))
                .pipe(rename({
                    extname: '.js'
                }))
                .pipe(gulp.dest(paths.build.jsx));
            });
            
        es.merge(tasks).on('end', done);
    })
});

/**
 * Компилируем .js файлы
 */
gulp.task('js:build', function (done) { 

    glob(paths.src.js, function(err, files) {
         
        if(err) done(err);
        

        var tasks = files.map(function(entry) {
            
            // Вычесляем имя файла, чтоб задать скомпилированному файлу то же имя
            var filename = (slash2dash(entry));
            
            return gulp.src(entry)
                    .pipe(rename({
                        basename: exclude_ext(filename,'js')
                    }))
                    .pipe(gulp.dest(paths.build.js));
        });
            
        es.merge(tasks).on('end', done);
    })
    
});

gulp.task('watch',['jsx:build', 'js:build'], function () {
    gulp.watch(paths.src.jsx, ['jsx:build']);
    gulp.watch(paths.src.js, ['js:build']);
});

gulp.task('clean', function(){
    return gulp.src(paths.clean)
            .pipe(clean());
});

gulp.task('default', ['watch']);