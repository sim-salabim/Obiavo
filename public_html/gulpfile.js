/**
 * На данном этапе удут компилироваться только jsx файлыю
 * Включена поддержка ES6
 * @type Module gulp|Module gulp
 */

var gulp = require('gulp'),
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
    },
    
    build: { //Тут мы укажем куда складывать готовые после сборки файлы        
        jsx: 'frontend/web/dist/js',
    },    
    watch: { //Тут мы укажем, за изменением каких файлов мы хотим наблюдать
        jsx: 'frontend/web/js/react-components/**/*.jsx',
    },
    clean: 'frontend/web/dist'
};

var slash2dash = function(str) { return str.replace(/\//g,'--'); } // Небольшая функция для преобразования слешей в тирэ

//Получить имя файла
var fname = function(path_to_file){    
    return path_to_file.replace(/^.*[\\\/]/, '');
};

/*
gulp.task("babel", function(){
    return gulp.src(paths.jsx.from).
        pipe(babel({
            plugins: ['transform-react-jsx'],
            presets: ['es2015', 'react']
        }))
        .pipe(source('.compress_build.js'))
        pipe(gulp.dest(paths.jsx.to));
});
*/

/**
 * Компилируем все .jsx(es6 & es5) 
 */
gulp.task('js:build', function (done) {        
     
     glob(paths.src.jsx, function(err, files) {
         
        if(err) done(err);
        

        var tasks = files.map(function(entry) {
            
            // Вычесляем имя файла, чтоб задать скомпилированному файлу то же имя
            var filename = slash2dash(entry);
            
            return browserify({ entries: [entry], extensions: ['.jsx'], debug: true })
                .transform('babelify', {presets: ['es2015', 'react']})
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


gulp.task('watch',['js:build'], function () {
    gulp.watch(paths.src.jsx, ['js:build']);
});

gulp.task('default', ['watch']);