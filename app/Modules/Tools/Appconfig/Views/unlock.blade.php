@extends('layout.app')

@push('plugin-styles')
    <style>
        .centered {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
@endpush

@section('content')
@include('layout.partials.breadcrumb',compact('breadcrumb'))
<!-- Default Light Table -->
<div class="row justify-content-center">
    <div class="col-md-6">
        <form action="{{ route($module . '.unlockconfig') }}" id='my-form' method="post">
            @csrf
            <div class="card card-small mb-1">
                <div class="card-header border-bottom pb-2 pt-2">
                    <h5>Autentikasi Hak Akses Appconfig</h5>
                </div>
                <div class="card-body form-autentikasi">
                    <div class="form-group">
                        <label for="password" class="col-form-label">Password</label>
                        <div class="input-group">
                            <input type="password"
                                class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                name="password" id="password" autocomplete="off" placeholder="Password">
                            <a class="input-group-text toggle-password cursor-pointer bg-white" style="cursor: pointer;">
                                <i class="bx bx-show bx-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Default Light Table -->

@endsection

@push('plugin-scripts')
<script type="text/javascript">
    $('.toggle-password').click(function() {
        const passwordField = $('#password');
        const fieldType = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', fieldType);

        const iconHtml = fieldType === 'password' ?
        '<i class="bx bx-show bx-xs"></i>' :
        '<i class="bx bx-hide bx-xs"></i>';

        $(this).html(iconHtml);

    });
    
    $(function(){
        initPage();

        $('form#my-form').submit(function(e){
            e.preventDefault();
            $(this).myAjax({
                waitMe: '.form-autentikasi',
                success: function (data) {
                    window.location.href = "{{ route($module.'.index') }}";
                }
            }).submit({
                confirm: {
                    title: 'Submit autentikasi hak akses?',
                    text: ''
                },
                success: {
                    title: 'Autentikasi berhasil!',
                    text: '',
                }
            });
        });
    })
</script>
@endpush
