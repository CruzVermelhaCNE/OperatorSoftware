const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/auth/app.js', 'public/js/auth')
    .js('resources/js/salop/app.js', 'public/js/salop')
    .js('resources/js/covid19/app.js', 'public/js/covid19')
    //.scripts(['resources/js/map.js'], 'public/js/map.js')
    .js('resources/js/map.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/dashboard.scss', 'public/css')
    .sass('resources/sass/theaters_of_operations.scss', 'public/css')
    .js('resources/js/laravel-echo-setup.js', 'public/js').sourceMaps().version();
