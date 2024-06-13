@extends('layout.modal')

@section('title', __('Form Edit'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => [$module . '.update', encrypt($data->id)], 'method' => 'put', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">
        <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label">{{ __('Nama') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="name" id="name" value="{{ $data->name }}">
            </div>
        </div>
        <div class="form-group row">
            <label for="code" class="col-sm-3 col-form-label">{{ __('Code') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="code" id="code" value="{{ $data->code }}">
            </div>
        </div>
        <div class="form-group row">
            <label for="roles" class="col-sm-3 col-form-label">{{ __('Peran') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                {{ Form::select('roles[]', $role_options, $roles, ['class' => 'select2']) }}
                <div id="error_roles"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">{{ __('Status') }}</label>
            <div class="col-sm-9">
                <div class="cb-dynamic-label custom-control custom-toggle custom-toggle-sm mb-1 mt-1">
                    <input type="checkbox" id="" name="status" value="1" class="custom-control-input" {{ $data->status == '1' ? 'checked="checked"' : '' }}  data-text-on="{{ __('Aktif') }}" data-text-off="{{ __('Tidak Aktif') }}" >
                    <label class="custom-control-label text-muted" for="status"></label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Tutup') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
    </div>
    {!! Form::close() !!}
@endsection

@push('plugin-scripts')
<script type="text/javascript">
  $(function(){
    initPage();

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
