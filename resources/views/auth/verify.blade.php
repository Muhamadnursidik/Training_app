@extends('default.layouts.login')

@section('content')
<div class="row h-100">
    <div class="col-sm-9 col-md-7 col-lg-4 mx-auto my-auto">
        <div class="card card-signin">
            <div class="card-body">
                <img class="auth-form__logo d-table mx-auto mb-3" src="/images/shards-dashboards-logo.svg" alt="Shards Dashboards - Register Template">
                <h5 class="card-title text-center">{{ __('Verify Your Email Address') }}</h5>

                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif

                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
            </div>
        </div>
    </div>
</div>
@endsection
