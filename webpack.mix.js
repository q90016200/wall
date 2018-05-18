let mix = require('laravel-mix');

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

// mix.react('resources/assets/js/vendor.js', 'public/js');
// mix.sass('resources/assets/sass/vendor.scss', 'public/css');

mix.react('resources/assets/js/wall/wall_index.js','public/js');


