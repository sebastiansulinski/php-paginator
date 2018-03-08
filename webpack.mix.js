let mix = require('laravel-mix');

mix
    .setPublicPath('resources/dist')
    .js('resources/src/js/app.js', 'js')
    .sass('resources/src/scss/app.scss', 'css');
