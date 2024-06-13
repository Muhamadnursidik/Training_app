<!DOCTYPE html>
<html>
<head>
  <title>{{ config('data.app_name','Compliance Regulatory Mangement System') }} | Login</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

  @stack('plugin-styles')

  <!-- common css -->
  <link href="{{ asset(mix('css/bootstrap.min.css')) }}" rel="stylesheet" />
  <link href="{{ asset(mix('css/icons.min.css')) }}" rel="stylesheet" />
  <link href="{{ asset(mix('css/app.min.css')) }}" rel="stylesheet" />
  <link href="{{ asset(mix('css/custom.min.css')) }}" rel="stylesheet" />
  <!-- end common css -->

  @stack('style')
</head>
<body data-base-url="{{url('/')}}">

  <div class="main-wrapper" id="app">
    <div class="page-wrapper full-page">
      @yield('content')
    </div>
  </div>

    <!-- base js -->
    <script src="{{ asset(mix('js/app.all.js')) }}"></script>
    <!-- end base js -->

    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->
    @stack('custom-scripts')
</body>
</html>
