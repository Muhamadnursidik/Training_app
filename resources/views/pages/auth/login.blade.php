@extends('layout.login')

@section('content')
    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden">
            <div class="container">
                
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card mb-0">
                            <div class="row g-0">
                                <div class="col-lg-6">
                                    <div class="auth-one-bg h-100">
                                        <div class="bg-overlay"></div>
                                        <div class="position-relative h-100 d-flex flex-column">
                                            <div class="mb-4">
                                                <a href="#" class="d-block">
                                                    
                                                </a>
                                            </div>
                                            <div class="mt-auto">
                                                <div class="mb-3">
                                                    <i class="ri-double-quotes-l display-4 text-success"></i>
                                                </div>

                                                <div id="qoutescarouselIndicators" class="carousel slide"
                                                    data-bs-ride="carousel">
                                                    <div class="carousel-indicators">
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                            data-bs-slide-to="0" class="active" aria-current="true"
                                                            aria-label="Slide 1"></button>
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                            data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                    
                                                    </div>
                                                    <div class="carousel-inner text-center text-white-50 pb-5">
                                                        <div class="carousel-item active">
                                                            <p class="fs-15 fst-italic">" Kami bekerja secara Amanah dan Kompeten, dengan membangun hubungan yang Harmonis dan sikap Loyal, menjalankan bisnis secara Adaptif, serta mengembangkan kerjasama Kolaboratif.
                                                                "</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15 fst-italic">" We work competently and trustworthily while building a harmonic relationship loyally that we operate adaptively while establishing collaborative corporation."</p>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <!-- end carousel -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->

                                <div class="col-lg-6">
                                    <div class="p-lg-3x p-3">
                                        <div class="text-center">
                                            @if (file_exists(public_path() . '/img/logo.png'))
                                                <img src="{{ asset('img/logo.png') }}" width="160" height="18"
                                                    class="img-fluid" alt="Responsive image">
                                            @else
                                                <img src="{{ asset('img/logo-bartech.png') }}" width="160" height="18"
                                                    class="img-fluid" alt="Responsive image">
                                            @endif
                                            <h5 class="text-primary mt-3">{{ config('data.app_name') }}</h5>
                                        </div>

                                        <div class="mt-4">
                                            <form class="forms-sample" action="{{ route('login') }}" method="POST"
                                                novalidate autocomplete="off">
                                                @csrf
                                                <div class="mb-3">
                                                    <label
                                                        for="{{ config('data.login_auth_with') }}">{{ ucfirst(config('data.login_auth_with') ? config('data.login_auth_with') : 'username') }}</label>

                                                    <input type="text" name="{{ config('data.login_auth_with') ? config('data.login_auth_with') : 'username' }}"
                                                        class="form-control {{ $errors->has(config('data.login_auth_with')) ? ' is-invalid' : '' }}"
                                                        id="{{ config('data.login_auth_with') }}"
                                                        placeholder="Masukan {{ config('data.login_auth_with') ? config('data.login_auth_with') : 'username' }}"
                                                        autocomplete="off">

                                                    @if ($errors->has(config('data.login_auth_with')))
                                                        <span class="text-danger">
                                                            <strong>{{ $errors->first(config('data.login_auth_with')) }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="mb-3">
                                                    <label for="exampleInputPassword1" class="form-label">Password</label>
                                                    <div class="position-relative auth-pass-inputgroup">
                                                        <input type="password"
                                                            class="form-control password-input {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                            name="password" id="password" autocomplete="off"
                                                            placeholder="Masukan Password">
                                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon">
                                                            <i class='bx bx-low-vision'></i>
                                                        </button>
                                                    </div>
                                                    @if ($errors->has('password'))
                                                        <span class="text-danger" role="alert">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                @if (config('data.login_with_captcha') == 'true')
                                                    <div class="form-group mb-3">
                                                        <div class="input-group">
                    
                                                            <button type="button" id="reload-captcha"
                                                                class="btn btn-soft-primary btn-sm"><span>&#x21bb;</span></button>
                                                            <img class="captcha-image" id="captcha-img" src="{!! captcha_src('flat') !!}">
                    
                                                            <input type="text" id="captcha" placeholder="Masukan Captcha"
                                                                class="form-control {{ $errors->has('captcha') ? ' is-invalid' : '' }}"
                                                                name="captcha" autocomplete="off">
                                                            @if ($errors->has('captcha'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('captcha') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="auth-remember-check">
                                                    <label class="form-check-label" for="auth-remember-check">Remember
                                                        me</label>
                                                </div>

                                                <div class="mt-4">
                                                    <button class="btn btn-primary w-100" type="submit">Sign In</button>
                                                </div>

                                                

                                            </form>
                                        </div>

                                    </div>
                                </div>
                                <!-- end col -->
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0">Copyright &copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script>
                                {{ config('data.client_name') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->
@endsection

@push('custom-scripts')
    <script>
        $('#reload-captcha').click(function() {
            // Generate new captcha image URL
            var newSrc = '{!! captcha_src('flat') !!}?' + Math.random();
            // Update captcha image src attribute
            $('.captcha-image').attr('src', newSrc);
            // Clear captcha input field
            $('#captcha').val('');
        });
        $('#password-addon').click(function() {
            const passwordField = $('#password');
            const fieldType = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', fieldType);

            const iconHtml = fieldType === 'password' ?
            '<i class="bx bx-low-vision"></i>' :
            '<i class="bx bx-show"></i>';

            $(this).html(iconHtml);
        });
    </script>
@endpush
