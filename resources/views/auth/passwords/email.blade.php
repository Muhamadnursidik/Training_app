@extends('default.layouts.login')

@section('content')
<div class="row h-100">
    <div class="col-sm-9 col-md-7 col-lg-4 mx-auto my-auto">
        <div class="card card-signin">
            <div class="card-body">
                <img class="auth-form__logo d-table mx-auto mb-3" src="/images/shards-dashboards-logo.svg" alt="Shards Dashboards - Register Template">
                <h5 class="card-title text-center">{{ __('Reset Password') }}</h5>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="form-signin" autocomplete="off">
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

                    <button type="submit" class="btn btn-lg btn-primary btn-block text-uppercase">
                        {{ __('Send Password Reset Link') }}
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
