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
    .sass('resources/sass/app.scss', 'public/css');

mix.js('resources/js/utils.js', 'public/js/utils.js');

// MaterializeCSS
mix.js(['node_modules/materialize-css/dist/js/materialize.js'], 'public/js/materialize.js');
mix.styles(['node_modules/materialize-css/dist/css/materialize.css'], 'public/css/materialize.css');
