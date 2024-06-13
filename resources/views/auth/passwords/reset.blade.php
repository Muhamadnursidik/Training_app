@extends('default.layouts.login')

@section('content')
<div class="row h-100">
    <div class="col-sm-9 col-md-7 col-lg-4 mx-auto my-auto">
        <div class="card card-signin">
            <div class="card-body">
                <img class="auth-form__logo d-table mx-auto mb-3" src="/images/shards-dashboards-logo.svg" alt="Shards Dashboards - Register Template">
                <h5 class="card-title text-center">{{ __('Reset Password') }}</h5>
                <form method="POST" action="{{ route('password.update') }}" class="form-signin" autocomplete="off">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-label-group">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" placeholder="{{ __('Email address') }}">
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
                        {{ __('Reset Password') }}
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
