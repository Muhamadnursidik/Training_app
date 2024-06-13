@extends('default.layouts.login')

@section('content')
<div class="row h-100">
    <div class="col-sm-9 col-md-7 col-lg-4 mx-auto my-auto">
        <div class="card card-signin">
            <div class="card-body">
                <img class="auth-form__logo d-table mx-auto mb-3" src="/images/shards-dashboards-logo.svg" alt="Shards Dashboards - Register Template">
                <h5 class="card-title text-center">{{ __('Sign In') }} </h5>
                <form method="POST" action="{{ route('login') }}" class="form-signin" autocomplete="off">
                    @csrf

                    <div class="form-label-group">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('Email address') }}" autofocus>
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

                    <div class="custom-control custom-checkbox mb-3">
                        <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary btn-block text-uppercase">
                        {{ __('Login') }}
                    </button>

                </form>
            </div>
        </div>
        <div class="row">
            <div class="d-flex mt-4 w-100">
                @if (Route::has('password.request'))
                <a class="btn btn-link text-muted" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
