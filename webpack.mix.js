let mix = require('laravel-mix');

mix
    .js('resources/src/js/app.js', 'resources/dist/js')
    .sass('resources/src/scss/app.scss', 'resources/dist/css');
