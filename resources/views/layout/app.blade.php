<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="gradient-4" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-bs-theme="light" data-layout-width="fluid" data-layout-position="fixed" data-layout-style="default" data-sidebar-visibility="show">
<head>
    <title>{{ config('data.app_name', 'Penilaian Sendiri Risiko Operasional (PERISKOP)') }} {{ isset($pageTitle) ? ' | ' . $pageTitle : '' }}
        {{ isset($pageSubTitle) ? ' ' . $pageSubTitle : '' }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- plugin css -->
    <link href="{{ asset(mix('assets/plugins/sweetalert2/sweetalert2.min.css')) }}" rel="stylesheet" />
    <link href="{{ asset(mix('assets/plugins/select2/select2.min.css')) }}" rel="stylesheet" />
    <link href="{{ asset(mix('assets/plugins/waitme/waitMe.min.css')) }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset(mix('assets/plugins/datatables-net/dataTables.bootstrap5.min.css')) }}" rel="stylesheet" type="text/css" />
    <!-- end plugin css -->

    @stack('plugin-styles')

    <!-- common css -->
    <link href="{{ asset(mix('css/bootstrap.min.css')) }}" rel="stylesheet" />
    <link href="{{ asset(mix('css/icons.min.css')) }}" rel="stylesheet" />
    <link href="{{ asset(mix('css/app.min.css')) }}" rel="stylesheet" />
    <link href="{{ asset(mix('css/custom.min.css')) }}" rel="stylesheet" />
    <!-- end common css -->

    @stack('style')
</head>

<body data-base-url="{{ url('/') }}">
    <!-- Begin page -->
    <div class="layout-wrapper">
        {{-- header --}}
        @include('layout.partials.header')
        {{-- end header --}}

        {{-- sidebar --}}
        @include('layout.partials.sidebar')
        <div class="vertical-overlay"></div>
        {{-- end sidebar --}}

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            @include('layout.partials.footer')
        </div>
    </div>
    <!-- END layout-wrapper -->

    @include('layout.partials.modal')
    <!-- base js -->
    <script>var loading = `<div class="text-center"><i class='bx bx-loader-alt bx-spin bx-sm'></i></div>`;</script>
    <script src="{{ asset(mix('js/app.all.js')) }}"></script>
    <script src="{{ asset(mix('assets/plugins/waitme/waitMe.min.js')) }}"></script>
    <script src="{{ asset(mix('assets/plugins/toastr/toastr.min.js')) }}"></script>
    <script src="{{ asset(mix('assets/plugins/select2/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('assets/plugins/simplebar/simplebar.min.js')) }}"></script>
    <script src="{{ asset(mix('js/page.min.js')) }}"></script>
    <script src="{{ asset(mix('js/recursivefilter.min.js')) }}"></script>
    <!-- end base js -->

    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->

    <!-- common js -->
    <script src="{{ asset('js/template.js') }}"></script>
    <!-- end common js -->

    @stack('custom-scripts')
</body>

</html>
