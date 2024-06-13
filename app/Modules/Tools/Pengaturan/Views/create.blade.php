@extends('layout.modal')

@section('title', __('Form Tambah'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => $module . '.store', 'method' => 'post', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">
        <div class="form-group m-0 row p-0">
            <label for="code" class="col-sm-3 col-form-label">{{ __('Code') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" name="code" id="code">
            </div>
        </div>
        <div class="form-group m-0 row p-0">
            <label class="col-sm-3 col-form-label">{{ __('Status') }}</label>
            <div class="col-sm-9">
                <div class="form-check form-switch mb-1 mt-1">
                    <input type="checkbox" id="status" name="status" value="1" class="form-check-input" checked="checked" data-text-on="{{ __('Aktif') }}" data-text-off="{{ __('Tidak Aktif') }}" >
                    <label class="custom-control-label text-muted" for="status"></label>
                </div>
            </div>
        </div>
        @include('tools'.DIRECTORY_SEPARATOR.'appconfig::config')
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
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
              $('.tambah').remove();
              $('.modal').modal('hide');
              oTable.reload();
          }
      }).submit();
    });
  })
</script>
@endpush
