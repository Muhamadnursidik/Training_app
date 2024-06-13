@extends('layout.modal')

@section('title', __('Form Tambah'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => $module . '.store', 'method' => 'post', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">
        <div class="form-group m-1 row p-0">
            <label for="name" class="col-sm-3 col-form-label">{{ __('Nama') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control " name="name" id="name">
            </div>
        </div>
        <div class="form-group m-1 row p-0">
            <label for="email" class="col-sm-3 col-form-label">{{ __('Email') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control " name="email" id="email">
            </div>
        </div>
        <div class="form-group m-1 row p-0">
            <label for="username" class="col-sm-3 col-form-label">{{ __('Username') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control " name="username" id="username">
            </div>
        </div>
        <div class="form-group m-1 row p-0">
            <label for="password" class="col-sm-3 col-form-label">{{ __('Password') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <div class="position-relative auth-pass-inputgroup">
                    <input type="password" class="form-control " name="password" id="password">
                    <button
                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                        type="button" id="new-password-addon" data-target="#password" onclick="showPassword(this)">
                        <i class='bx bx-hide'></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="form-group m-1 row p-0">
            <label for="roles" class="col-sm-3 col-form-label">{{ __('Peran') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                {{ Form::select('roles[]', $role_options, null, ['class' => 'select2modal']) }}
                <div id="error_roles"></div>
            </div>
        </div>
        <div class="form-group m-1 row p-0">
            <label for="unit_kerja_code" class="col-sm-3 col-form-label">{{ __('Unit Kerja') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                {{ Form::select('unit_kerja_code', $options_unit_kerja, null, ['class' => 'select2modal']) }}
                <div id="error_unit_kerja_code"></div>
            </div>
        </div>
        <div class="form-group m-1 row p-0">
            <label class="col-sm-3 col-form-label">{{ __('Sebagai Admin') }}</label>
            <div class="col-sm-9">
                <div class="form-check form-switch mb-1 mt-2">
                    <input type="checkbox" id="is_admin" name="is_admin" value="1" class="form-check-input" data-text-on="{{ __('Aktif') }}" data-text-off="{{ __('Tidak Aktif') }}" >
                    <label class="custom-control-label text-muted" for="is_admin"></label>
                </div>
            </div>
        </div>
        <div class="form-group m-1 row p-0">
            <label class="col-sm-3 col-form-label">{{ __('Sebagai Reviewer') }}</label>
            <div class="col-sm-9">
                <div class="form-check form-switch mb-1 mt-2">
                    <input type="checkbox" id="is_reviewer" name="is_reviewer" value="1" class="form-check-input" data-text-on="{{ __('Aktif') }}" data-text-off="{{ __('Tidak Aktif') }}" >
                    <label class="custom-control-label text-muted" for="is_reviewer"></label>
                </div>
            </div>
        </div>
        <div class="form-group m-1 row p-0">
            <label class="col-sm-3 col-form-label">{{ __('View Semua Unitkerja') }}</label>
            <div class="col-sm-9">
                <div class="form-check form-switch mb-1 mt-2">
                    <input type="checkbox" id="view_all_unit" name="view_all_unit" value="1" class="form-check-input" data-text-on="{{ __('YA') }}" data-text-off="{{ __('TIDAK') }}" >
                    <label class="custom-control-label text-muted" for="view_all_unit"></label>
                </div>
            </div>
        </div>
        <div class="form-group m-1 row p-0">
            <label class="col-sm-3 col-form-label">{{ __('Status') }}</label>
            <div class="col-sm-9">
                <div class="form-check form-switch mb-1 mt-2">
                    <input type="checkbox" id="" name="status" value="1" class="form-check-input" checked="checked" data-text-on="{{ __('Aktif') }}" data-text-off="{{ __('Tidak Aktif') }}" >
                    <label class="custom-control-label text-muted" for="status"></label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
    </div>
    {!! Form::close() !!}
@endsection

@push('plugin-scripts')
<script type="text/javascript">
    function showPassword(el) {
        var passwordField   = $(el).data('target');
        var fieldType       = $(passwordField).attr('type') === 'password' ? 'text' : 'password';
        $(passwordField).attr('type', fieldType);

        var iconHtml = fieldType === 'password' ?
            '<i class="bx bx-hide"></i>' :
            '<i class="bx bx-show"></i>';

        $(el).html(iconHtml);
    }
  $(function(){
    initPage();

    $('.select2modal').select2({
        width: '100%',
        theme: "bootstrap-5",
        dropdownParent: $('#modal-lg')
    });

    $('form#my-form').submit(function(e){
      e.preventDefault();
      $(this).myAjax({
          waitMe: '.modal-content',
          success: function (data) {
              $('.modal').modal('hide');
              oTable.reload();
          }
      }).submit();
    });
  })
</script>
@endpush
