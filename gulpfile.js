var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

// Gentelella vendors path : vendor/bower_components/gentelella/vendors

elixir(function(mix) {
    
    /********************/
    /* Copy Stylesheets */
    /********************/

    // Bootstrap
    mix.copy('vendor/bower_components/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css', 'public/css/bootstrap.min.css');

    // Font awesome
    mix.copy('vendor/bower_components/gentelella/vendors/font-awesome/css/font-awesome.min.css', 'public/css/font-awesome.min.css');

    // Gentelella
    mix.copy('vendor/bower_components/gentelella/build/css/custom.min.css', 'public/css/gentelella.min.css');

    // Datetimepicker for Bootstrap 3
    mix.copy('vendor/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css', 'public/css/bootstrap-datetimepicker.min.css');

    //DataTable
    mix.copy('vendor/bower_components/datatables.net-dt/css/jquery.dataTables.min.css', 'public/css/jquery.dataTables.min.css');
    mix.copy('vendor/bower_components/datatables.net-dt/images/', 'public/images');

    /****************/
    /* Copy Scripts */
    /****************/

    // Bootstrap
    mix.copy('vendor/bower_components/gentelella/vendors/bootstrap/dist/js/bootstrap.min.js', 'public/js/bootstrap.min.js');

    // jQuery
    mix.copy('vendor/bower_components/gentelella/vendors/jquery/dist/jquery.min.js', 'public/js/jquery.min.js');

    // Gentelella
    mix.copy('vendor/bower_components/gentelella/build/js/custom.min.js', 'public/js/gentelella.min.js');

    // Datetimepicker for Bootstrap 3
    mix.copy('vendor/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js', 'public/js/bootstrap-datetimepicker.min.js');

    // Moment
    mix.copy('vendor/bower_components/moment/locale/zh-cn.js', 'public/js/moment/zh-cn.js');
    mix.copy('vendor/bower_components/moment/min/moment.min.js', 'public/js/moment.min.js');

    //DataTable
    mix.copy('vendor/bower_components/datatables.net/js/jquery.dataTables.min.js', 'public/js/jquery.dataTables.min.js');

    /**************/
    /* Copy Fonts */
    /**************/

    // Bootstrap
    mix.copy('vendor/bower_components/gentelella/vendors/bootstrap/fonts/', 'public/fonts');

    // Font awesome
    mix.copy('vendor/bower_components/gentelella/vendors/font-awesome/fonts/', 'public/fonts');
});
