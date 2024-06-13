@extends('default.layouts.login')

@section('content')
<div class="row h-100">
    <div class="col-sm-9 col-md-7 col-lg-4 mx-auto my-auto">
        <div class="card card-signin">
            <div class="card-body">
                <img class="auth-form__logo d-table mx-auto mb-3" src="/images/shards-dashboards-logo.svg" alt="Shards Dashboards - Register Template">
                <h5 class="card-title text-center">{{ __('Sign Up') }}</h5>
                <form method="POST" action="{{ route('register') }}" class="form-signin">
                    @csrf

                    <div class="form-label-group">
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" placeholder="{{ __('Name') }}" value="{{ old('name') }}" autofocus>
                        <label for="name">{{ __('Name') }}</label>

                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-label-group">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('Email address') }}">
                        <label for="email">{{ __('Email address') }}</label>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-label-group">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}">
                        <label for="password">{{ __('Password') }}</label>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-label-group">
                        <input id="password-confirm" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" placeholder="{{ __('Confirm Password') }}">
                        <label for="password-confirm">{{ __('Confirm Password') }}</label>

                        @if ($errors->has('password_confirmation'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary btn-block text-uppercase">
                        {{ __('Register') }}
                    </button>

                </form>
            </div>

            <div class="card-footer">
                <ul class="auth-form__social-icons d-table mx-auto">
                  <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                  <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                  <li><a href="#"><i class="fab fa-github"></i></a></li>
                  <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
