@extends('layout.modal')

@section('title', __('Form Tambah Libur Nasional'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => $module . '.store', 'method' => 'post', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">
        <div class="form-group row mb-1">
            <label for="tanggal" class="col-sm-3 col-form-label">{{ __('Tanggal') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="date" class="form-control" name="tanggal" id="tanggal" required>
            </div>
        </div>
        <div class="form-group row mb-1">
            <label for="keterangan" class="col-sm-3 col-form-label">{{ __('Keterangan') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="keterangan" id="keterangan" required>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
    </div>
    {!! Form::close() !!}
@endsection

@push('plugin-scripts')
<script type="text/javascript">
  $(function(){
    // Submit pake AJAX nu geus ada
    $('form#my-form').submit(function(e){
      e.preventDefault();
      $(this).myAjax({
          waitMe: '.modal-content',
          success: function (data) {
              $('.modal').modal('hide');
              oTable.reload(); // reload datatable lamun aya
              alert('Data berhasil disimpan!');
          },
          error: function(xhr){
              alert('Gagal nyimpen: ' + xhr.responseJSON?.message || 'Unknown error');
          }
      }).submit();
    });
  });
</script>
@endpush
