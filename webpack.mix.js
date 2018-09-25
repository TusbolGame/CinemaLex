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
mix.js('resources/assets/js/users/app.js', 'public/js')
   .js('resources/assets/js/admin/app.js', 'public/js/admin-js/')
  .extract(['vue',
            'plyr',
            'vee-validate',
            'video.js',
            'videojs-flash',
            'videojs-resolution-switcher',
          ]);
mix.sass('resources/assets/sass/app.scss', 'public/css');

