@extends('layout.modal')

@section('title', __('Form Tambah Data Mitra'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => $module . '.store', 'method' => 'post', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">
        <div class="form-group row p-0 mb-1">
            <label for="kode_mitra" class="col-sm-3 col-form-label">{{ __('Kode Mitra') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control " name="kode_mitra" id="kode_mitra">
            </div>
        </div>
        <div class="form-group row p-0 mb-1">
            <label for="nama_mitra" class="col-sm-3 col-form-label">{{ __('Nama Mitra') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control " name="nama_mitra" id="nama_mitra">
            </div>
        </div>
        <div class="form-group row p-0 mb-1">
            <label for="alamat" class="col-sm-3 col-form-label">{{ __('Alamat') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <textarea class="form-control" name="alamat" id="alamat" rows="3"></textarea>
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
