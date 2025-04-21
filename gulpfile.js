// import * as dartSass from 'sass' //Obtenemos todas las funciones de SASS
import * as dartSass from 'sass' //Obtenemos todas las funciones de SASS
import gulpSass from 'gulp-sass'
import {src, dest, watch} from 'gulp'
import sourcemaps from 'gulp-sourcemaps'
import imagemin from 'gulp-imagemin';



//const autoprefixer = require('autoprefixer');

// Debemos estipular que gulpSass debe utilizar todas las funciones de sass
const sass = gulpSass(dartSass);//esta es la unión de dos primeras líneas

export function css( done ){
    // src('src/scss/app.scss') //Ruta de origen
    src('src/scss/**/*.scss') //Ruta de origen
        .pipe(sourcemaps.init()) //Inicialiar sourcemaps
        .pipe( sass().on('error', sass.logError) ) //Proceso de compilación
        .pipe(sourcemaps.write('.')) //Escribir el sorucemaps
        .pipe( dest('public/build/css') ) //Ruta destino
    done();
}

export function javascript( done ) {
    src('src/js/**/*.js')
    //   .pipe(terser())
    //   .pipe( sourcemaps.init() )
    //   .pipe( sourcemaps.write('.') ) 
      .pipe( dest('public/build/js') )
    done();
}

export function imagenes(done) { 
    src('src/img/**/*') 
        // .pipe(imagemin()) // Opcional: optimizar imágenes 
        .pipe(dest('public/build/img')); 
    done(); 
}

// Creamos una función para ejecutar el watch
export function dev(){
    // Este watch escucha los cambios realizados en la ruta dada, y aplica las instrucciónes de la función css
    watch('src/scss/**/*.scss', css);
    watch('src/js/**/*.js', javascript);
    watch('src/img/**/*', imagenes);
    //se pueden colocar todos los watch que se deseen
}