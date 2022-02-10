let mix = require('laravel-mix')

mix
  .setPublicPath('public/dist')
  .js('resources/src/js/app.js', 'js')
  .vue()
  .postCss('resources/src/css/app.css', 'css', [
    require('tailwindcss'),
  ])
