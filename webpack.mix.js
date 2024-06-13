const { EnvironmentPlugin } = require('webpack');
const mix = require('laravel-mix');
const glob = require('glob');
const path = require('path');
const WebpackShellPluginNext = require('webpack-shell-plugin-next');
const destination = 'public/assets/plugins/';
/*
 |--------------------------------------------------------------------------
 | Configure Webpack
 |--------------------------------------------------------------------------
 */

mix.webpackConfig({
  stats: {
    children: true,
  },
  plugins:
    [
      new WebpackShellPluginNext({ onBuildStart: ['php artisan lang:js --quiet'], onBuildEnd: [] })
    ]
});

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

mix.autoload({
  jquery: ['$', 'jQuery'],
});


mix
  .js("resources/js/app.js", "public/js")
  .js("resources/js/spinner.js", "public/js")
  .scripts(["resources/js/helpers/datatable.js"], "public/js/datatable.min.js")
  .scripts(
    [
      "public/js/app.js",
      "public/js/messages.js",
      "resources/js/helpers/common.js",
    ],
    "public/js/app.all.js"
  )
  .sass("resources/scss/app.scss", "public/css/app.min.css")
  .sass("resources/scss/custom.scss", "public/css/custom.min.css")
  .sass("resources/scss/bootstrap.scss", "public/css/bootstrap.min.css")
  .sass("resources/scss/icons.scss", "public/css/icons.min.css")
  ;

if (mix.inProduction()) {
  mix.version();
}

// Copy plugin files to public folder
mix
  .copyDirectory(
    ["resources/js/graph.js"],
    "public/js"
  )
  .copyDirectory(
    ["resources/js/helpers/recursivefilter.js"],
    "public/js/recursivefilter.min.js"
  )
  .copyDirectory(
    "node_modules/feather-icons/dist",
    "public/assets/plugins/feather-icons"
  )
  .copyDirectory(
    ["node_modules/toastr/build/toastr.min.css", "node_modules/toastr/build/toastr.min.js"],
    "public/assets/plugins/toastr"
  )
  .copyDirectory(
    "node_modules/flag-icon-css",
    "public/assets/plugins/flag-icon-css"
  )
  .copyDirectory("node_modules/@mdi/font", "public/assets/plugins/@mdi")
  .copyDirectory(
    [
      "node_modules/perfect-scrollbar/dist",
      "node_modules/perfect-scrollbar/css",
    ],
    "public/assets/plugins/perfect-scrollbar"
  )
  .copyDirectory(
    [
      "node_modules/prismjs/prism.js",
      "node_modules/prismjs/plugins/normalize-whitespace/prism-normalize-whitespace.min.js",
      "node_modules/prismjs/themes/prism.css",
    ],
    "public/assets/plugins/prismjs"
  )
  .copyDirectory(
    "node_modules/clipboard/dist/clipboard.min.js",
    "public/assets/plugins/clipboard/clipboard.min.js"
  )
  .copyDirectory(
    ["node_modules/jquery-numeric/jquery.numeric.js"],
    "public/assets/plugins/jquery-numeric/jquery.numeric.js"
  )
  .copyDirectory(
    [
      "node_modules/cropperjs/dist/cropper.min.js",
      "node_modules/cropperjs/dist/cropper.min.css",
    ],
    "public/assets/plugins/cropperjs"
  )
  .copyDirectory(
    "node_modules/owl.carousel/dist",
    "public/assets/plugins/owl-carousel"
  )
  .copyDirectory(
    "node_modules/jquery-mousewheel/jquery.mousewheel.js",
    "public/assets/plugins/jquery-mousewheel/jquery.mousewheel.js"
  )
  .copyDirectory(
    "node_modules/animate.css/animate.min.css",
    "public/assets/plugins/animate-css/animate.min.css"
  )
  .copyDirectory(
    [
      "node_modules/sweetalert2/dist/sweetalert2.min.js",
      "node_modules/sweetalert2/dist/sweetalert2.min.css",
    ],
    "public/assets/plugins/sweetalert2"
  )
  .copyDirectory(
    "node_modules/promise-polyfill/dist/polyfill.min.js",
    "public/assets/plugins/promise-polyfill/polyfill.min.js"
  )

  .copyDirectory(
    "node_modules/chart.js/dist/Chart.min.js",
    "public/assets/plugins/chartjs/Chart.min.js"
  )
  .copyDirectory("node_modules/jquery.flot", "public/assets/plugins")
  .copyDirectory(
    "node_modules/flot.curvedlines/curvedLines.js",
    "public/assets/plugins/flot-curvedlines/curvedLines.js"
  )
  .copyDirectory(
    "node_modules/jquery.flot.tooltip/js/jquery.flot.tooltip.min.js",
    "public/assets/plugins/jquery-flot-tooltip/jquery.flot.tooltip.min.js"
  )
  .copyDirectory(
    "node_modules/apexcharts/dist/apexcharts.min.js",
    "public/assets/plugins/apexcharts/apexcharts.min.js"
  )
  .copyDirectory(
    "node_modules/peity/jquery.peity.min.js",
    "public/assets/plugins/peity/jquery.peity.min.js"
  )
  .copyDirectory(
    "node_modules/jquery-sparkline/jquery.sparkline.min.js",
    "public/assets/plugins/jquery-sparkline/jquery.sparkline.min.js"
  )

  .copyDirectory(
    [
      "node_modules/datatables.net/js/jquery.dataTables.js",
      "node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css",
      "node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js"
    ],
    "public/assets/plugins/datatables-net"
  )

  .copyDirectory(
    "node_modules/bootstrap-datepicker/dist",
    "public/assets/plugins/bootstrap-datepicker"
  )
  .copyDirectory(
    [
      "node_modules/select2/dist/js/select2.min.js",
      "node_modules/select2/dist/js/select2.full.min.js",
      "node_modules/select2/dist/css/select2.min.css",
      "node_modules/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css",
    ],
    "public/assets/plugins/select2"
  )
  .copyDirectory(
    [
      "node_modules/select2/src/js/select2",
    ],
    "public/assets/plugins/select2/select2"
  )
  .copyDirectory(
    [
      "node_modules/simplemde/dist/simplemde.min.js",
      "node_modules/simplemde/dist/simplemde.min.css",
    ],
    "public/assets/plugins/simplemde"
  )
  .copyDirectory(
    "node_modules/jquery-tags-input/dist",
    "public/assets/plugins/jquery-tags-input"
  )
  .copyDirectory(
    [
      "node_modules/dropzone/dist/min/dropzone.min.js",
      "node_modules/dropzone/dist/min/dropzone.min.css",
    ],
    "public/assets/plugins/dropzone"
  )
  .copyDirectory("node_modules/dropify/dist", "public/assets/plugins/dropify")
  .copyDirectory(
    [
      "node_modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js",
      "node_modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css",
    ],
    "public/assets/plugins/bootstrap-colorpicker"
  )
  .copyDirectory(
    [
      "node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js",
      "node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css",
    ],
    "public/assets/plugins/bootstrap-datepicker"
  )
  .copyDirectory(
    "node_modules/jquery-validation/dist/jquery.validate.min.js",
    "public/assets/plugins/jquery-validation/jquery.validate.min.js"
  )
  .copyDirectory(
    "node_modules/inputmask/dist/jquery.inputmask.min.js",
    "public/assets/plugins/inputmask/jquery.inputmask.bundle.min.js"
  )
  .copyDirectory(
    "node_modules/typeahead.js/dist/typeahead.bundle.min.js",
    "public/assets/plugins/typeahead-js/typeahead.bundle.min.js"
  )
  .copyDirectory("node_modules/tinymce", "public/assets/plugins/tinymce")
  .copyDirectory(
    "node_modules/ace-builds/src-min",
    "public/assets/plugins/ace-builds"
  )
  .copyDirectory(
    [
      "node_modules/jquery-steps/build/jquery.steps.min.js",
      "node_modules/jquery-steps/demo/css/jquery.steps.css",
    ],
    "public/assets/plugins/jquery-steps"
  )
  .copyDirectory(
    "node_modules/@fortawesome/fontawesome-free",
    "public/assets/plugins/font-awesome"
  )
  .copyDirectory(
    [
      "node_modules/fullcalendar/dist/fullcalendar.min.js",
      "node_modules/fullcalendar/dist/fullcalendar.min.css",
    ],
    "public/assets/plugins/fullcalendar"
  )
  .copyDirectory(
    "node_modules/jquery-ui-dist/jquery-ui.min.js",
    "public/assets/plugins/jquery-ui-dist/jquery-ui.min.js"
  )
  .copyDirectory(
    "node_modules/moment/min/moment.min.js",
    "public/assets/plugins/moment/moment.min.js"
  )
  .copyDirectory(
    "node_modules/handlebars/dist/handlebars.min.js",
    "public/assets/plugins/handlebars/handlebars.min.js"
  )
  .copyDirectory(
    ["node_modules/waitme/waitMe.min.js", "node_modules/waitme/waitMe.min.css"],
    "public/assets/plugins/waitme/"
  )
  .copyDirectory(
    ["node_modules/jquery-number/jquery.number.min.js"],
    "public/assets/plugins/jquery-number/"
  )
  .copyDirectory(
    [
      "node_modules/daterangepicker/daterangepicker.js",
      "node_modules/daterangepicker/daterangepicker.css",
    ],
    "public/assets/plugins/daterangepicker/"
  )
  .copyDirectory(
    [
      "node_modules/highcharts/highcharts.js",
      "node_modules/highcharts/highcharts.js.map",
      "node_modules/highcharts/highcharts-3d.js",
      "node_modules/highcharts/highcharts-more.js",
      "node_modules/highcharts/css/highcharts.css",
    ],
    "public/assets/plugins/highcharts/"
  )
  .copyDirectory(
    ["node_modules/highcharts/modules"],
    "public/assets/plugins/highcharts/modules"
  )
  .copyDirectory(
    [
      "node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js",
      "node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css",
      "node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput-typeahead.css"
    ],
    "public/assets/plugins/bootstrap-tagsinput"
  )
  .copyDirectory(
    [
      "node_modules/typeahead.js/dist/typeahead.bundle.min.js",
      "node_modules/typeahead.js/dist/typeahead.jquery.min.js",
      "node_modules/typeahead.js/dist/bloodhound.min.js"
    ],
    "public/assets/plugins/typeahead"
  ).copyDirectory(
    [
      "node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js",
      "node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js.map"
    ],
    "public/assets/plugins/ckeditor"
  ).copyDirectory(
    ["node_modules/@ckeditor/ckeditor5-build-classic/build/translations"],
    "public/assets/plugins/ckeditor/translations"
  ).copyDirectory(
    ["node_modules/bootstrap-tokenfield/dist/bootstrap-tokenfield.min.js",
      "node_modules/bootstrap-tokenfield/dist/css/bootstrap-tokenfield.min.css",
      "node_modules/bootstrap-tokenfield/dist/css/tokenfield-typeahead.min.css",
    ],
    "public/assets/plugins/bootstrap-tokenfield"
  ).copyDirectory(
    ["node_modules/suggestags/js/jquery.amsify.suggestags.js",
      "node_modules/suggestags/css/amsify.suggestags.css",
    ],
    "public/assets/plugins/suggestags"
  ).copyDirectory(
    [
      "node_modules/@yaireo/tagify/dist/tagify.min.js",
      "node_modules/@yaireo/tagify/dist/tagify.polyfills.min.js",
      "node_modules/@yaireo/tagify/dist/jQuery.tagify.min.js",
      "node_modules/@yaireo/tagify/dist/tagify.css"
    ],
    "public/assets/plugins/tagify"
  )
  .copyDirectory(
    ["node_modules/nestable2/dist/jquery.nestable.min.css", "node_modules/nestable2/dist/jquery.nestable.min.js"],
    "public/assets/plugins/nestable2"
  )
  .copyDirectory(
    ["node_modules/simplebar/dist/simplebar.min.js", "node_modules/simplebar/dist/simplebar.min.css"],
    "public/assets/plugins/simplebar"
  )
  .copyDirectory(
    ['resources/js/helpers/page.js'],
    "public/js/page.min.js"
  )
  .copyDirectory(
    ['node_modules/line-awesome'],
    "public/assets/plugins/line-awesome"
  )
  .copyDirectory(
    ['node_modules/jquery-calx'],
    "public/assets/plugins/jquery-calx"
  )  
  .copyDirectory(
    ['node_modules/numeral'],
    "public/assets/plugins/numeral"
  )
  ;
