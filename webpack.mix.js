const mix = require('laravel-mix');

mix.styles([
    'resources/css/app.css',  // Path ke app.css di dalam folder resources
    'public/css/style.css'    // Path ke style.css di dalam folder public
], 'public/css/all.css');     // Output gabungan dari kedua file CSS akan disimpan di public/css/all.css
